@extends('layouts.default')
@section('content')
    <div class="hero">
        <div class="container">
            <div class="hero__inner">
                <img class="hero__img" src="{{asset('images/hero-woman.png')}}" alt="">
                <div class="row">
                    <div class="col-md-7">
                        <div class="hero__title">
                            group health fully-insured <span>provider search</span>
                        </div>
                        <div class="hero__sub-title">
                            Locate participating providers, facilities, and pharmacies.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="home-search">
        <div class="container">
            <div class="vue-app"></div>
        </div>
    </div>
@endsection
