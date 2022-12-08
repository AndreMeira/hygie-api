@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Hygie academie</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <br />

    @endif

    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-image">
                <img src="images/bilans-home.jpg">
                <span class="card-title"></span>
                </div>
                
                <div class="card-action">
                    <a href="https://bilanvitalite.hygieacademie.com/token/{{$token}}">Faire le test du bilan de vitalité</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-image">
                <img src="images/macros-home.jpg">
                <span class="card-title"></span>
                </div>
                
                <div class="card-action" >
                    <a href="https://macros.hygieacademie.com/token/{{$token}}">Calculer mes macros</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-image">
                <img src="images/profils-home.jpg">
                <span class="card-title"></span>
                </div>
                
                <div class="card-action">
                    <a href="https://profil.hygieacademie.com/token/{{$token}}">Faire le test du profil</a>
                </div>
            </div>
        </div>
  </div>  

    <br />
    
      
</div>
@endsection

