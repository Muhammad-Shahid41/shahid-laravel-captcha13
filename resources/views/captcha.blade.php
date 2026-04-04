<div class="captcha-wrapper">
    <img src="{{ url(config('captcha.route', 'captcha/image')) }}?t={{ time() }}" alt="captcha">
</div>
