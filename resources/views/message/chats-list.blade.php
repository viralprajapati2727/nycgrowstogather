<ul  class="nav nav-pills flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    @if(!empty($currentChats) && $currentChats->count())
        @foreach ($currentChats as $key => $group)
            @foreach ($group->members as $member)
                @if($member->user_id != $currentUser->id)
                    <li class="user-band" data-id="{{ $group->id }}">
                        @php
                            $ProfileUrl = Helper::images(config('constant.profile_url'));
                            $img_url = (isset($member->user->logo) && $member->user->logo != '') ? $ProfileUrl . $member->user->logo : $ProfileUrl.'default.png';


                            $activated = "";
                            if((!empty($user) && $user->id == $member->user->id) || (!empty($activated_group) && $activated_group == $group->id)){
                                $activated = "active show";
                            }


                            if(empty($user) && empty($activated_group)){
                                if($key == 0){
                                    $activated = "active show";
                                }
                            }

                            $groupMessageCount = Helper::groupMessageCount(Auth::id(), $group->id);

                        @endphp
                         <div class="profile-image-wrap">
                            <a href="{{ route('user.view-profile',['slug' => Auth::user()->slug]) }}">
                                <img class="chat-icons profile-image" height="50" src="{{  $img_url }}">
                            </a>
                        </div>
                        <a class="nav-link {{ $activated }}" data-toggle="pill" href="#chat{{ $group->id }}" role="tab" data-group="{{ $group->id }}">
                            <span class="user-name">{{ ucwords(($member->user->name ?? "name")) }}</span>
                        </a> 
                        @if(isset($groupMessageCount) && $groupMessageCount > 0) 
                            <div class="unread_count">{{ $groupMessageCount }}</div>
                        @endif
                    </li>
                @endif
            @endforeach
        @endforeach
    @endif
</ul>