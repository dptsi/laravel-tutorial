# Laravel broadcasting

[Kembali](../readme.md)

## Daftar isi

- [Langkah 1] daftar pusher
- [Langkah 2] setup environment
- [Langkah 3] setup event
- [Langkah 4] setup setup endpoint authentication
- [Langkah 5] setup frontend

Untuk frontend, langkah" yang dilakukan adalah pertama dengan melakukan inisialisasi pusher ke cluster dan sesuai app_key yang di tentukan, dan juga melakukan setup untuk authEndpoint dan auth headers. Jika ada authentikasi, kita dapat meletakkan token sebagai Authorization bearer di `headers`.

```js
// Only for React, pass csrf token in head.meta tags
const token = document.head.querySelector('meta[name="csrf-token"]').content;

const pusher = new Pusher(PUSHER_APP_KEY, {
  cluster: PUSHER_APP_CLUSTER,
  encrypted: true,
  authEndpoint: '/broadcasting/auth',
  auth: { headers: { 'X-CSRF-Token': token } },
});
```

> Jika menggunakan framework, maka kita harus melakukan passing csrf token ke head menggunakan blade

```html
<meta name="csrf-token" content="{{ csrf_token() }}" />
```

Setelah melakukan init, kita harus melakukan subscription ke channel. Jika channel merupakan private, maka dapat ditambahkan prefix `private-`

```js
// Channel Name
const channel = pusher.subscribe('message-notification');
```

Kemudian, membind dengan event yang akan masuk, nama event bisa langsung dikustomisasi dan kita bisa memilih event apa yang ingin kita bind sehingga kita mendapat data yang tidak tercampur". Fungsi callback pada `channel.bind`, akan terinvoke setiap kali ada event yang masuk ke pusher.

```js
// Event Name (new_product, new_friend_request)
channel.bind('message_created_boi', (data) => {
  // Set the data
  setChats((chats) => [...chats, data]);
});
```

Setelah menjalankan subscription dan bind event, maka kita bisa mendapatkan akses pada variable `chats` dalam kasus ini berupa React State dengan type sebagai berikut

```ts
const [chats, setChats] = useState<Chats[]>([]);

interface Chats {
  name_from: string;
  message: string;
}
```

Jika tidak menggunakan framework JS, maka bisa melakukan AJAX dan membuat dom node setiap kali ada chat yang masuk:

```js
function event_cb(data) {
  const e = document.createElement('div');
  e.innerHTML = data.message;
  document.doby.appendChild(e);
}
```
