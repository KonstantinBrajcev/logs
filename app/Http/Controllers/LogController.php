<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    // Метод для отображения логов
    // public function index()
    // {
    //     // Получаем все логи из базы данных
    //     $logs = Log::all();

    //     // Передаем логи в представление
    //     return view('logs', compact('logs'));
    // }

    public function index(Request $request)
    {
        // Записываем время начала
        $startTime = microtime(true);
        // Получаем все логи из базы данных
        $logs = Log::all();
        // Записываем время окончания
        $endTime = microtime(true);
        // Вычисляем длительность
        $duration = ($endTime - $startTime) * 1000; // В миллисекундах
        // Сохраняем информацию о запросе в таблицу logs
        Log::create([
            'ip_address' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'input' => json_encode($request->all()), // Сохраняем входные данные
            'time' => now(),
            'duration' => $duration,
        ]);

        // Получаем все логи из базы данных
        // $logs = Log::all();

        // Передаем логи в представление
        return view('logs', compact('logs'));
    }

    public function store(Request $request)
    {
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

        // Возврат ответа
        return response()->json(['message' => 'Log saved successfully']);
    }
}
