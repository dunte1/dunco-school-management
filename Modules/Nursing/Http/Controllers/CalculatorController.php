<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalculatorController extends Controller
{
    public function index()
    {
        return Inertia::render('Nursing/Calculators/Index');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bmi,unit_conversion,fluid_balance,iv_flow,percentage,weight_conversion,time_conversion',
            'values' => 'required|array',
        ]);

        $type = $request->type;
        $values = $request->values;
        $result = null;

        switch ($type) {
            case 'bmi':
                $weight = (float) ($values['weight'] ?? 0);
                $height = (float) ($values['height'] ?? 0);
                if ($height > 0) {
                    $heightM = $height / 100;
                    $bmi = $weight / ($heightM * $heightM);
                    $category = match(true) {
                        $bmi < 18.5 => 'Underweight',
                        $bmi < 25 => 'Normal weight',
                        $bmi < 30 => 'Overweight',
                        default => 'Obese',
                    };
                    $result = [
                        'value' => round($bmi, 1),
                        'category' => $category,
                        'formula' => 'BMI = weight (kg) / height (m)²',
                        'steps' => [
                            "Height in meters: {$height} cm ÷ 100 = {$heightM} m",
                            "BMI = {$weight} ÷ ({$heightM} × {$heightM})",
                            "BMI = {$weight} ÷ " . round($heightM * $heightM, 4),
                            "BMI = " . round($bmi, 1),
                        ],
                    ];
                }
                break;

            case 'percentage':
                $value = (float) ($values['value'] ?? 0);
                $total = (float) ($values['total'] ?? 1);
                if ($total > 0) {
                    $pct = ($value / $total) * 100;
                    $result = [
                        'value' => round($pct, 2),
                        'formula' => 'Percentage = (value / total) × 100',
                        'steps' => [
                            "Percentage = ({$value} / {$total}) × 100",
                            "Percentage = " . round($pct, 2) . "%",
                        ],
                    ];
                }
                break;

            case 'weight_conversion':
                $weight = (float) ($values['weight'] ?? 0);
                $from = $values['from'] ?? 'kg';
                $to = $values['to'] ?? 'lbs';
                $conversions = [
                    'kg_to_lbs' => 2.20462,
                    'lbs_to_kg' => 0.453592,
                    'kg_to_g' => 1000,
                    'g_to_kg' => 0.001,
                ];
                $key = $from . '_to_' . $to;
                if (isset($conversions[$key])) {
                    $converted = $weight * $conversions[$key];
                    $result = [
                        'value' => round($converted, 2),
                        'formula' => "{$weight} {$from} × {$conversions[$key]} = " . round($converted, 2) . " {$to}",
                        'steps' => [
                            "{$weight} {$from} × {$conversions[$key]} = " . round($converted, 2) . " {$to}",
                        ],
                    ];
                }
                break;

            case 'fluid_balance':
                $input = (float) ($values['input'] ?? 0);
                $output = (float) ($values['output'] ?? 0);
                $balance = $input - $output;
                $result = [
                    'value' => round($balance, 1),
                    'formula' => 'Balance = Input - Output',
                    'steps' => [
                        "Input: {$input} mL",
                        "Output: {$output} mL",
                        "Balance: {$input} - {$output} = " . round($balance, 1) . " mL",
                        $balance > 0 ? 'Positive balance (fluid retention)' : ($balance < 0 ? 'Negative balance (fluid deficit)' : 'Neutral balance'),
                    ],
                ];
                break;

            case 'iv_flow':
                $volume = (float) ($values['volume'] ?? 0);
                $time = (float) ($values['time'] ?? 1);
                $dropFactor = (float) ($values['drop_factor'] ?? 20);
                if ($time > 0) {
                    $mlPerHour = $volume / $time;
                    $dropsPerMin = ($volume * $dropFactor) / ($time * 60);
                    $result = [
                        'value' => round($mlPerHour, 1),
                        'drops_per_minute' => round($dropsPerMin, 1),
                        'formula' => 'Rate = Volume / Time',
                        'steps' => [
                            "Volume: {$volume} mL",
                            "Time: {$time} hours",
                            "Drop factor: {$dropFactor} drops/mL",
                            "ML/hour = {$volume} / {$time} = " . round($mlPerHour, 1) . " mL/hr",
                            "Drops/min = ({$volume} × {$dropFactor}) / ({$time} × 60) = " . round($dropsPerMin, 1) . " drops/min",
                        ],
                    ];
                }
                break;

            case 'time_conversion':
                $hours = (float) ($values['hours'] ?? 0);
                $result = [
                    'value' => $hours,
                    'steps' => [
                        "{$hours} hours = " . ($hours * 60) . " minutes",
                        "{$hours} hours = " . ($hours * 3600) . " seconds",
                    ],
                ];
                break;

            case 'unit_conversion':
                $value = (float) ($values['value'] ?? 0);
                $fromUnit = $values['from'] ?? '';
                $toUnit = $values['to'] ?? '';

                $conversions = [
                    'mcg_to_mg' => ['factor' => 0.001, 'formula' => 'mg = mcg / 1000'],
                    'mg_to_mcg' => ['factor' => 1000, 'formula' => 'mcg = mg × 1000'],
                    'mg_to_g' => ['factor' => 0.001, 'formula' => 'g = mg / 1000'],
                    'g_to_mg' => ['factor' => 1000, 'formula' => 'mg = g × 1000'],
                    'g_to_kg' => ['factor' => 0.001, 'formula' => 'kg = g / 1000'],
                    'kg_to_g' => ['factor' => 1000, 'formula' => 'g = kg × 1000'],
                    'ml_to_l' => ['factor' => 0.001, 'formula' => 'L = mL / 1000'],
                    'l_to_ml' => ['factor' => 1000, 'formula' => 'mL = L × 1000'],
                    'c_to_f' => ['custom' => true, 'formula' => '°F = (°C × 9/5) + 32'],
                    'f_to_c' => ['custom' => true, 'formula' => '°C = (°F - 32) × 5/9'],
                    'cm_to_in' => ['factor' => 0.393701, 'formula' => 'in = cm × 0.393701'],
                    'in_to_cm' => ['factor' => 2.54, 'formula' => 'cm = in × 2.54'],
                    'lb_to_kg' => ['factor' => 0.453592, 'formula' => 'kg = lb × 0.453592'],
                    'kg_to_lb' => ['factor' => 2.20462, 'formula' => 'lb = kg × 2.20462'],
                    'oz_to_g' => ['factor' => 28.3495, 'formula' => 'g = oz × 28.3495'],
                ];

                $key = strtolower($fromUnit) . '_to_' . strtolower($toUnit);
                if (isset($conversions[$key])) {
                    $conv = $conversions[$key];
                    if (isset($conv['custom'])) {
                        if ($key === 'c_to_f') {
                            $converted = ($value * 9 / 5) + 32;
                        } else {
                            $converted = ($value - 32) * 5 / 9;
                        }
                    } else {
                        $converted = $value * $conv['factor'];
                    }
                    $result = [
                        'value' => round($converted, 4),
                        'formula' => $conv['formula'],
                        'steps' => [
                            "{$value} {$fromUnit} × " . ($conv['factor'] ?? 'conversion') . " = " . round($converted, 4) . " {$toUnit}",
                            $conv['formula'],
                        ],
                    ];
                } else {
                    $result = [
                        'value' => $value,
                        'steps' => ["Conversion from {$fromUnit} to {$toUnit} is not in the standard medical conversion table."],
                    ];
                }
                break;
        }

        if ($result) {
            $result['disclaimer'] = 'This is an educational calculator for learning purposes only. Always follow institutional protocols and verify calculations independently.';
        }

        return response()->json(['result' => $result]);
    }
}
