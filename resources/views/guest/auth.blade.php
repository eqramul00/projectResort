@include('auth.lamp', [
    'authMode' => !empty($register) ? 'register' : 'guest'
])