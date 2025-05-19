
<style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');

    a{
        text-decoration: none;
        font-family: "Cairo", sans-serif;

    }
</style>

<div style="background-image: linear-gradient(rgba(33,33,34,0.4), rgba(33,33,34,0.8)),url('{{ asset('bg.jpg') }}'); background-size: cover;
    background-repeat: no-repeat; height: 100vh; display: flex; justify-content: center; align-items: center;">

<div class="container">
    <div style="">

        <div style="box-shadow: 0 5px 83px 0 rgba(40, 40, 40, 0.11); background-color: white; padding: 40px; border-radius: 8px; margin: 40px;">
            {{-- <i class="fe fe-briefcase"></i> --}}
            <a style="color: #3cb9c7; font-weight: bold; font-size: 18px;" href="{{url('admin')}}">تسجيل كمسؤول</a>
        </div>

     <div style="box-shadow: 0 5px 83px 0 rgba(40, 40, 40, 0.11); background-color: white; padding: 40px; border-radius: 8px; margin: 40px;">
        {{-- <i class="fe fe-user-tie"></i> --}}
       <a style="color: #3cb9c7; font-weight: bold; font-size: 18px;" href="{{url('partner')}}">تسجيل كمكتب</a>
     </div>
    </div>
</div>

    </div>

