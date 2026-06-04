@extends('layouts.member')

@section('header_title', 'Chat Team')

@section('content')

<div class="bg-white rounded-3xl shadow overflow-hidden h-[80vh] flex">

```
<!-- Sidebar -->
<div class="w-80 border-r border-slate-200 flex flex-col">

    <div class="p-5 border-b">
        <h2 class="font-bold text-xl">
            Chat Team 💬
        </h2>
        <p class="text-sm text-slate-500">
            Hari Ini: {{ now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    <div class="overflow-y-auto flex-1">

        <div onclick="loadChat(1)"
             class="chat-item p-4 border-b cursor-pointer hover:bg-slate-50">

            <div class="flex justify-between">
                <h3 class="font-semibold">
                    Project Pemrograman Web
                </h3>

                <span class="bg-red-500 text-white text-xs px-2 rounded-full">
                    3
                </span>
            </div>

            <p class="text-sm text-slate-500">
                Nadhif: Layout sudah final?
            </p>

        </div>

        <div onclick="loadChat(2)"
             class="chat-item p-4 border-b cursor-pointer hover:bg-slate-50">

            <h3 class="font-semibold">
                Project Auka Hub
            </h3>

            <p class="text-sm text-slate-500">
                Meeting jam 2 siang
            </p>

        </div>

        <div onclick="loadChat(3)"
             class="chat-item p-4 border-b cursor-pointer hover:bg-slate-50">

            <h3 class="font-semibold">
                E-Commerce App
            </h3>

            <p class="text-sm text-slate-500">
                API pembayaran selesai
            </p>

        </div>

        <div onclick="loadChat(4)"
             class="chat-item p-4 border-b cursor-pointer hover:bg-slate-50">

            <h3 class="font-semibold">
                Internal Tools
            </h3>

            <p class="text-sm text-slate-500">
                Report module done
            </p>

        </div>

    </div>

</div>

<!-- Chat Area -->
<div class="flex-1 flex flex-col">

    <div class="border-b p-5">

        <h2 id="chatTitle"
            class="font-bold text-xl">
            Project Pemrograman Web
        </h2>

    </div>

    <div id="chatMessages"
         class="flex-1 overflow-y-auto bg-slate-50 p-5 space-y-4">

    </div>

    <div id="typingIndicator"
         class="hidden px-5 py-2 text-sm text-slate-500">

        💬 Sedang mengetik...

    </div>

    <div class="border-t p-4">

        <div class="flex gap-3">

            <input
                id="messageInput"
                type="text"
                placeholder="Ketik pesan..."
                class="flex-1 border rounded-2xl px-4 py-3">

            <button
                onclick="sendMessage()"
                class="bg-blue-600 text-white px-6 rounded-2xl">

                Kirim

            </button>

        </div>

    </div>

</div>
```

</div>

<script>

const chats = {

1:{
title:'Project Pemrograman Web',
messages:[
{
sender:'Nadhif',
time:'09:10',
text:'Untuk Project Pemrograman Web, apakah layout sudah final?'
},
{
sender:'Vion',
time:'09:12',
text:'Iya, tinggal optimasi responsif dan koneksi API backend.'
},
{
sender:'Aisyah',
time:'09:15',
text:'Aku akan bantu verifikasi UI dan deploy ke staging.'
}
]
},

2:{
title:'Project Auka Hub',
messages:[
{
sender:'Nadhif',
time:'08:00',
text:'Meeting jam 2 siang ya.'
}
]
},

3:{
title:'E-Commerce App',
messages:[
{
sender:'Vion',
time:'10:00',
text:'API pembayaran selesai.'
}
]
},

4:{
title:'Internal Tools',
messages:[
{
sender:'Nadhif',
time:'13:00',
text:'Report module done.'
}
]
}

};

let activeChat = 1;

function loadChat(id){

activeChat = id;

document.getElementById('chatTitle').innerText =
chats[id].title;

renderMessages();

}

function renderMessages(){

const box =
document.getElementById('chatMessages');

box.innerHTML='';

chats[activeChat].messages.forEach(msg=>{

const mine =
msg.sender === 'Aisyah';

box.innerHTML += `
<div class="flex ${mine ? 'justify-end':'justify-start'}">

<div class="${mine ? 'bg-blue-600 text-white':'bg-white'} px-4 py-3 rounded-2xl shadow max-w-md">

<div class="font-semibold text-sm">
${msg.sender}
</div>

<div>
${msg.text}
</div>

<div class="text-xs opacity-70 mt-1">
${msg.time}
</div>

</div>

</div>
`;

});

box.scrollTop =
box.scrollHeight;

}

function sendMessage(){

const input =
document.getElementById('messageInput');

if(input.value.trim()==='') return;

const now = new Date();

const time =
String(now.getHours()).padStart(2,'0')
+ ':'
+
String(now.getMinutes()).padStart(2,'0');

const text = input.value;

chats[activeChat].messages.push({

sender:'Aisyah',
time:time,
text:text

});

renderMessages();

input.value='';

simulateReply(text);

}

function simulateReply(message){

const typing =
document.getElementById('typingIndicator');

typing.classList.remove('hidden');

setTimeout(()=>{

typing.classList.add('hidden');

let sender = 'Nadhif';
let reply = '';

const msg =
message.toLowerCase();

if(msg.includes('selesai')){

reply =
'Progress sudah 80% dan tinggal testing.';

}

else if(msg.includes('meeting')){

sender='Vion';

reply =
'Meeting hari ini jam 2 siang ya.';

}

else if(msg.includes('api')){

sender='Vion';

reply =
'API sedang proses integrasi frontend.';

}

else if(msg.includes('deploy')){

reply =
'Deploy staging malam ini.';

}

else if(msg.includes('deadline')){

reply =
'Deadline hari Jumat jam 17.00.';

}

else if(msg.includes('halo')){

reply =
'Halo Aisyah 👋';

}

else{

reply =
'Baik, saya catat dulu ya.';

}

const now = new Date();

const time =
String(now.getHours()).padStart(2,'0')
+ ':'
+
String(now.getMinutes()).padStart(2,'0');

chats[activeChat].messages.push({

sender:sender,
time:time,
text:reply

});

renderMessages();

},2000);

}

document
.getElementById('messageInput')
.addEventListener('keypress',function(e){

if(e.key === 'Enter'){

sendMessage();

}

});

loadChat(1);

</script>

@endsection
