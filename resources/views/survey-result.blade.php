<div class="container">
    <h1>Resultat</h1>
    <p>{{$result->completed_at}}</p>

    <div>
      <h3>ton score : {{$result->score}}</h3>
      <h2>{{$result->result}}</h2>
    </div>
    <div>
      {{$result->conclusion}}
    </div>
    <hr />
    <div>
      @foreach ($result->questions as $question)
          <h4>{{$question['label']}}</h4>
          <ul>
          @foreach ($question['response'] as $response)
            <li>{{$response}}</li>
          @endforeach
        </ul>
        <p>
          {{$question['comment']}}
        </p>
        <hr />
      @endforeach
    <div>
</div>
/*
210 15 38
