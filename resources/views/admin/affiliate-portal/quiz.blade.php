@extends('admin.layouts.main')

@section('title', 'Training Quiz')

@section('content')
    <div class="page-header"><h3 class="fw-bold mb-3">Training Quiz</h3></div>

    @if ($questions->isEmpty())
        <p class="text-muted">No quiz questions available yet — check back later.</p>
    @else
        <form method="POST" action="{{ route('affiliate.quiz.submit') }}"
    onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerText = 'Submitting...';">
    @csrf
            @csrf
            @foreach ($questions as $i => $q)
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="fw-bold">{{ $i + 1 }}. {{ $q->question }}</p>
                        @foreach (['a', 'b', 'c', 'd'] as $opt)
                            @if ($q->{"option_$opt"})
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" id="q{{ $q->id }}_{{ $opt }}" required>
                                    <label class="form-check-label" for="q{{ $q->id }}_{{ $opt }}">{{ $q->{"option_$opt"} }}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
            <button class="btn btn-success">Submit Quiz</button>
        </form>
    @endif
@endsection
