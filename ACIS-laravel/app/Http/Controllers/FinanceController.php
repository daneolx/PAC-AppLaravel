<?php

namespace App\Http\Controllers;

use App\Models\{Enrollment, Installment, Payment};
use App\Services\MoodleService;
use App\Services\CulqiService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinanceController extends Controller
{
    protected $moodleService;
    protected $culqiService;

    public function __construct(MoodleService $moodleService, CulqiService $culqiService)
    {
        $this->moodleService = $moodleService;
        $this->culqiService = $culqiService;
    }
    public function paymentIndex(Request $request)
    {
        $payments = Payment::with(['enrollment.alumno', 'createdByUser'])
            ->orderByDesc('payment_date')
            ->paginate(20);
        return view('finance.payment_list', compact('payments'));
    }

    public function paymentCreate(Enrollment $enrollment)
    {
        return view('finance.payment_form', ['enrollment' => $enrollment, 'methods' => Payment::METHODS]);
    }

    public function paymentStore(Request $request, Enrollment $enrollment)
    {
        $data = $request->validate([
            'amount'       => 'required|numeric|min:0.01',
            'method'       => ['required', Rule::in(array_keys(Payment::METHODS))],
            'payment_date' => 'required|date',
            'reference'    => 'nullable|string|max:100',
            'notes'        => 'nullable|string',
        ]);

        $data['enrollment_id']  = $enrollment->id;
        $data['payment_status'] = Payment::STATUS_PENDING;
        $data['created_by']     = $request->user()->id;

        Payment::create($data);

        return redirect()->route('student_financial_detail', $enrollment)
            ->with('success', 'Pago registrado. Pendiente de validación.');
    }

    public function paymentValidate(Payment $payment)
    {
        $payment->update([
            'payment_status' => Payment::STATUS_VALIDATED,
            'decided_by'     => request()->user()->id,
            'decided_at'     => now(),
        ]);
        $payment->applyToInstallments();

        // Si es el primer pago o está pagada gran parte, habilitar en Moodle
        $enrollment = $payment->enrollment;
        if (!$enrollment->habilitado_en_moodle && $enrollment->product->moodle_course_id) {
            $this->activateStudentInMoodle($enrollment);
        }

        return back()->with('success', 'Pago validado y aplicado a cuotas. Alumno verificado para Moodle.');
    }

    private function activateStudentInMoodle(Enrollment $enrollment)
    {
        $alumno = $enrollment->alumno;
        $moodleUser = $this->moodleService->getUserByEmail($alumno->email);

        if (!$moodleUser) {
            $password = \Illuminate\Support\Str::random(10) . 'A1!';
            $moodleUser = $this->moodleService->createUser([
                'username'  => $alumno->numero_documento,
                'password'  => $password,
                'firstname' => $alumno->nombre,
                'lastname'  => $alumno->apellido_paterno . ' ' . $alumno->apellido_materno,
                'email'     => $alumno->email,
            ]);
            
            if (isset($moodleUser['error'])) return;
            $moodleUserId = $moodleUser[0]['id'] ?? null;
        } else {
            $moodleUserId = $moodleUser['id'];
        }

        if ($moodleUserId) {
            $this->moodleService->enrollUserInCourse($moodleUserId, $enrollment->product->moodle_course_id);
            $enrollment->update([
                'habilitado_en_moodle' => true,
                'moodle_user_id'       => $moodleUserId
            ]);
        }
    }

    public function paymentReject(Request $request, Payment $payment)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $payment->update([
            'payment_status'   => Payment::STATUS_REJECTED,
            'decided_by'       => $request->user()->id,
            'decided_at'       => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        return back()->with('success', 'Pago rechazado.');
    }

    public function studentFinancialDetail(Enrollment $enrollment)
    {
        $enrollment->load(['alumno', 'product', 'installments', 'recordedPayments.createdByUser']);
        return view('finance.student_financial_detail', compact('enrollment'));
    }

    public function installmentPlan(Request $request, Enrollment $enrollment)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'installments'            => 'required|array|min:1',
                'installments.*.amount'   => 'required|numeric|min:0.01',
                'installments.*.due_date' => 'required|date',
            ]);

            $enrollment->installments()->delete();
            foreach ($request->installments as $i => $inst) {
                Installment::create([
                    'enrollment_id'      => $enrollment->id,
                    'installment_number' => $i + 1,
                    'amount'             => $inst['amount'],
                    'due_date'           => $inst['due_date'],
                    'created_by'         => $request->user()->id,
                ]);
            }

            return redirect()->route('student_financial_detail', $enrollment)
                ->with('success', 'Plan de cuotas creado.');
        }

        return view('finance.installment_plan_form', compact('enrollment'));
    }

    public function enableMoodle(Enrollment $enrollment)
    {
        // TODO: integrar con servicio Moodle
        $enrollment->update(['habilitado_en_moodle' => true]);
        return back()->with('success', 'Alumno habilitado en Moodle.');
    }

    public function alumnoPayEnrollment(Enrollment $enrollment)
    {
        return view('finance.alumno_pay_enrollment', compact('enrollment'));
    }

    public function culqiWebhook(Request $request)
    {
        // TODO: implementar webhook Culqi (validación de cargo)
        return response()->json(['status' => 'ok']);
    }
}
