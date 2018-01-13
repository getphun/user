# user

Adalah modul yang meyediakan service `user` yang bertugas mengidentifikasi user
yang sedang login melalui data cookie yang dikirim. Modul ini juga menyediakan
properti-properti user yang sedang login. Dan beberapa method-method yang biasa
digunakan oleh user.

Service ini bisa diakses dari kontroler dengan perintah `$this->user`.

Untuk membatasi user hanya boleh login dengan satu credential saja, tambahkan
konfigurasi berikut pada konfigurasi aplikasi:

```php
<?php

return [
    'name' => 'Phun',
    ...,
    'user' => [
        'loginBy' => [
            'name'  => false,
            'email' => true,
            'phone' => false,
            'social'=> true
        ]
    ]
];
```

Dengan konfigurasi seperti di atas, maka user hanya bisa login dengan email atau
akun sosial.