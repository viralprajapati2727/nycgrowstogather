@extends('layouts.app')
@section('content')
<div class="page-main p-0">
    <div class="front-banner our-team">
        <div class="content-wraper">
            <div class="container">
                <div class="content">
                    <h1 class="text-white">Our Team</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="page-wraper">
       <div class="our-team-wraper">
            <div class="container">
                <div class="team-lists">
                    <div class="row">
                        @if(!empty($teams) && $teams->count())
                            @forelse ($teams as $team)
                                <div class="col-md-4 col-sm-6">
                                    <div class="team-wraper">
                                        <div class="team-media">
                                            @php
                                                $teamurl = Helper::images(config('constant.team_url'));
                                                $img_url = $teamurl.$team->src;
                                            @endphp
                                            <img src="{{ $img_url }}" alt="" class="w-100">
                                            <div class="team-desc">
                                                <p>{!! $team->description !!}</p>
                                            </div>
                                        </div>
                                        <div class="team-content">
                                            <h4 class="title">{{ $team->name }}</h4>
                                            <p class="designation">{{ $team->position }}</p>
                                            <div class="team-social-icons">
                                                <ul class="m-0 p-0">
                                                    <li><a href="mailto:{{ $team->email }}"><i class="fa fa-envelope"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            @empty
                                
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>
       </div>
    </div>
</div>
@endsection