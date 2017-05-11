# user

Adalah modul yang meyediakan service `user` yang bertugas mengidentifikasi user
yang sedang login melalui data cookie yang dikirim. Modul ini juga menyediakan
properti-properti user yang sedang login. Dan beberapa method-method yang biasa
digunakan oleh user.

Service ini bisa diakses dari kontroler dengan perintah `$this->user`.

Ada dua struktur tabel untuk tabel `user`, yang pertama adalah plain tabel dan
yang digunakan secara umum. Yang kedua adalah tabel dengan partisi ( di-comment
di file `install.sql` ). Gunakan struktur yang kedua jika aplikasi memungkinkan
menerima banyak sekali user.

Tabel `user_session` yang menyimpan data session login user di partisi secara
default menjadi 50 partisi, dengan key `hash`. Jika melakukan proses update/delete
secara manual ketabel ini, sangat disarankan update/delete berdasarkan `hash`.

```php

// Cara ini berjalan dengan baik, tapi lebih lambat
\User\Model\UserSession::remove(1);

// Cara ini berjalan dengan baik dan lebih cepat
\User\Model\UserSession::remove(['hash' => 'random-hash']);
```