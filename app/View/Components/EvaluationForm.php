<?php

namespace App\View\Components;

use App\Models\Evaluation;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EvaluationForm extends Component
{
  public function __construct(public string $id, public ?string $evaluated, public ?string $highestScore = "0")
  {
  }


  public function render(): View|Closure|string
  {
    $reEvaluate = false;
    if ($this->evaluated) {
      $teacherId = auth()->user()->teacher->id;
      $reEvaluate = !Evaluation::where('teacher_id', $teacherId)->where('homework_id', $this->id)->get()->isEmpty();
    }
    $score = $this->highestScore;
    return view('components.evaluation-form', compact('reEvaluate', 'score'));
  }
}
