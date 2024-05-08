@if(Request::is('/'))
<div class="hero__item set-bg" data-setbg="/front/img/hero/banner.jpg">
    <div class="hero__text">
        <span>KEEP MEMORIES</span>
        <h2>WITH BELOVED<br /> PERSON</h2>
        <p>Find Photo Studio and Save Your Moments</p>
        <a href="{{route('search-page')}}" class="primary-btn">BOOKING NOW</a>
    </div>
</div>
@endif
