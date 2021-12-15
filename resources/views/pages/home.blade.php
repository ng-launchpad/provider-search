@extends('layouts.default')
@section('content')
    <div id="vue-app"></div>

    <div class="home-search">
        <div class="container">
            <div class="vue-app">
                <search-block
                    v-bind:stateId="{{ $state }}"
                    v-bind:networks="{{ $networks }}"
                ></search-block>
            </div>
        </div>
    </div>
@endsection
