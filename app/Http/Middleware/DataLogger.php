<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\File;
use Closure;
use App\Models\Log;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;

class DataLogger
{
    private $start_time;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $this->start_time = microtime(true);
        return $next($request);
    }
    public function terminate($request, $response) //выполняется после ответа пользователю
    {
        if ( env('API_DATALOGGER', true)) {
            if ( env('API_DATALOGGER_USE_DB', true)) {
                $endTime = microtime(true);
                $log = new Log();
                $log->time = date('Y-m-d M:i:s');
                $log->duration = number_format($endTime - LARAVEL_START, 3);
                $log->ip = $request->ip();
                $log->url = $request->fullUrl();
                $log->method = $request->method();
                $log->input = $request->getContent();
                // $log->save(); //сохраняем в БД

                try {
                    $log->save();
                } catch (\Exception $e) {
                    Log::error('Ошибка при сохранении лога: ' . $e->getMessage());
                }


            } else {
                $endTime = microtime(true);
                $filename = 'api_datalogger_' . date('d-m-y') . 'log';
                $dataToLog = 'Time: ' . date('Y-m-d M:i:s') . "\n";
                $dataToLog = 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
                $dataToLog = 'IP_Address: ' . $request->ip() . "\n";
                $dataToLog = 'URL: ' . $request->fullUrl();
                $dataToLog = 'Method: ' . $request->method();
                $dataToLog = 'Input: ' . $request->getContent();
                File::append(storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("*", 20) . "\e\n");
                // \File::append(storage_path('logs/' . $filename), $dataToLog . "\n" . str_repeat("*", 20) . "\n");
                // File::append(storage_path('logs/' . $filename), $dataToLog . "\n" . str_repeat("*", 20) . "\n");
            }
        }
        Log::info('Terminate method called');
    }
}

class LogRequests
{
    public function handle($request, Closure $next)
    {
        // Выполнить запрос
        $response = $next($request);

        // Пример данных для сохранения
        $logData = [
            'time' => now(),
            'duration' => 120, // Здесь можно указать реальную продолжительность
            'ip' => $request->ip(),
            'url' => $request->url(),
            'method' => $request->method(),
            'input' => json_encode($request->all()),
        ];

        // Сохранение данных в таблицу logs
        Log::create($logData);

        return $response;
    }
}
    