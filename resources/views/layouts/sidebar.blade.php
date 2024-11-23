<div id="sidepanel">
    <div id="profile">
        <div class="wrap">
            <img id="profile-img" src="http://emilcarlsson.se/assets/mikeross.png" class="online" alt="" />
            <p>{{auth()->user()->name}}</p>
            <i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>
            <div id="status-options">
                <ul>
                    <li id="status-online" class="active"><span class="status-circle"></span>
                        <p>Online</p>
                    </li>
                    <li id="status-away"><span class="status-circle"></span>
                        <p>Away</p>
                    </li>
                    <li id="status-busy"><span class="status-circle"></span>
                        <p>Busy</p>
                    </li>
                    <li id="status-offline"><span class="status-circle"></span>
                        <p>Offline</p>
                    </li>
                </ul>
            </div>

        </div>
    </div>
    <hr>
    <div id="contacts">
        <ul>
            @forelse($users as $user)
            <li class="contact" data-id="{{$user->id}}">
                <div class="wrap">
                    <span class="contact-status online"></span>
                    <img src="{{asset('images/avatar.png')}}" alt="" />
                    <div class="meta">
                        <p class="name">{{$user->name}}</p>
                        <p class="preview">You just got LITT up, Mike.</p>
                    </div>
                </div>
            @empty
                <p class="text-center">No users found</p>

{{--            <li class="contact active">--}}
{{--                <div class="wrap">--}}
{{--                    <span class="contact-status busy"></span>--}}
{{--                    <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />--}}
{{--                    <div class="meta">--}}
{{--                        <p class="name">Harvey Specter</p>--}}
{{--                        <p class="preview">Wrong. You take the gun, or you pull out a bigger one. Or, you call--}}
{{--                            their bluff. Or, you do any one of a hundred and forty six other things.</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </li>--}}
                @endforelse
        </ul>
    </div>
    <div class="text-center">
        <form action="{{route('logout')}}" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>

</div>
