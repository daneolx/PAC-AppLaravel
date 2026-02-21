<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoodleService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.moodle.url'); // e.g., https://moodle.acis.edu.pe/webservice/rest/server.php
        $this->token = config('services.moodle.token');
    }

    /**
     * Realizar una llamada genérica a la API de Moodle.
     */
    protected function call(string $function, array $params = [])
    {
        $params['wstoken'] = $this->token;
        $params['wsfunction'] = $function;
        $params['moodlewsrestformat'] = 'json';

        try {
            $response = Http::post($this->baseUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['exception'])) {
                    Log::error('Moodle API Exception', $data);
                    return ['error' => true, 'message' => $data['message'], 'exception' => $data['exception']];
                }
                return $data;
            }

            Log::error('Moodle HTTP Error', ['status' => $response->status()]);
            return ['error' => true, 'message' => 'Error de conexión con Moodle.'];

        } catch (\Exception $e) {
            Log::error('Moodle Exception', ['message' => $e->getMessage()]);
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Crear un usuario en Moodle.
     */
    public function createUser(array $userData)
    {
        // $userData: username, password, firstname, lastname, email
        return $this->call('core_user_create_users', [
            'users' => [
                [
                    'username' => $userData['username'],
                    'password' => $userData['password'],
                    'firstname' => $userData['firstname'],
                    'lastname' => $userData['lastname'],
                    'email' => $userData['email'],
                    'auth' => 'manual',
                ]
            ]
        ]);
    }

    /**
     * Matricular a un usuario en un curso.
     */
    public function enrollUserInCourse(int $userId, int $courseId, int $roleId = 5) // 5 = Alumno
    {
        return $this->call('enrol_manual_enrol_users', [
            'enrolments' => [
                [
                    'roleid' => $roleId,
                    'userid' => $userId,
                    'courseid' => $courseId,
                ]
            ]
        ]);
    }

    /**
     * Buscar un usuario por email en Moodle.
     */
    public function getUserByEmail(string $email)
    {
        $result = $this->call('core_user_get_users', [
            'criteria' => [
                ['key' => 'email', 'value' => $email]
            ]
        ]);

        return (isset($result['users']) && count($result['users']) > 0) ? $result['users'][0] : null;
    }
}
