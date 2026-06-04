@extends('layouts.member')

@section('header_title', 'My Projects')

@section('content')

<div class="space-y-8">

```
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            My Projects 🚀
        </h1>
        <p class="text-slate-500 mt-1">
            Track your project progress and tasks.
        </p>
    </div>
</div>

<!-- PROJECT LIST -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

    <!-- Portfolio -->
    <div
        onclick="openProjectModal('portfolio')"
        class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer">

        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800">
                Portfolio Web
            </h2>

            <span class="text-3xl">🎨</span>
        </div>

        <div class="mt-5">
            <div class="w-full h-3 rounded-full bg-slate-100">
                <div class="h-3 rounded-full bg-blue-500 w-[65%]"></div>
            </div>

            <div class="flex justify-between mt-2 text-sm">
                <span class="text-slate-500">6 / 10 Tasks</span>
                <span class="font-semibold text-blue-600">65%</span>
            </div>
        </div>

    </div>

    <!-- Ecommerce -->
    <div
        onclick="openProjectModal('ecommerce')"
        class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer">

        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800">
                E-Commerce App
            </h2>

            <span class="text-3xl">🛒</span>
        </div>

        <div class="mt-5">
            <div class="w-full h-3 rounded-full bg-slate-100">
                <div class="h-3 rounded-full bg-purple-500 w-[40%]"></div>
            </div>

            <div class="flex justify-between mt-2 text-sm">
                <span class="text-slate-500">4 / 10 Tasks</span>
                <span class="font-semibold text-purple-600">40%</span>
            </div>
        </div>

    </div>

    <!-- Internal -->
    <div
        onclick="openProjectModal('internal')"
        class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer">

        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800">
                Internal Tools
            </h2>

            <span class="text-3xl">🛠️</span>
        </div>

        <div class="mt-5">
            <div class="w-full h-3 rounded-full bg-slate-100">
                <div class="h-3 rounded-full bg-green-500 w-full"></div>
            </div>

            <div class="flex justify-between mt-2 text-sm">
                <span class="text-slate-500">10 / 10 Tasks</span>
                <span class="font-semibold text-green-600">100%</span>
            </div>
        </div>

    </div>

</div>
```

</div>

<!-- MODAL -->

<div id="projectModal"
class="hidden fixed inset-0 z-50 bg-black/30 backdrop-blur-md flex items-center justify-center p-4">

```
<div class="bg-white rounded-3xl w-full max-w-lg p-8 relative">

    <button
        onclick="closeProjectModal()"
        class="absolute right-5 top-5 text-slate-400 hover:text-red-500 text-xl">
        ✕
    </button>

    <div class="text-center">

        <div class="text-2xl">🐾</div>

        <div class="text-6xl">
            🧕
        </div>

        <h2 id="modalProject"
        class="text-2xl font-bold mt-4 text-slate-800">
        </h2>

        <div class="text-2xl mt-2">🐾</div>

    </div>

    <!-- TASK LIST -->
    <div id="taskContainer"
    class="mt-8 space-y-3">
    </div>

</div>
```

</div>

<script>

const projects = {

portfolio : {

name : "Portfolio Web",

tasks : [

{
title : "Fix Header Responsive",
priority : "High",
status : "doing"
},

{
title : "Dark Mode UI",
priority : "Medium",
status : "todo"
},

{
title : "Landing Page",
priority : "Low",
status : "done"
}

]

},

ecommerce : {

name : "E-Commerce App",

tasks : [

{
title : "Setup Authentication",
priority : "High",
status : "todo"
},

{
title : "Shopping Cart",
priority : "Medium",
status : "doing"
}

]

},

internal : {

name : "Internal Tools",

tasks : [

{
title : "Create Report Module",
priority : "Low",
status : "done"
}

]

}

};

function openProjectModal(key){

    const project = projects[key];

    document.getElementById('modalProject').innerText =
        project.name;

    let html = '';

    project.tasks.forEach((task,index)=>{

        html += `
        <div class="border rounded-2xl p-4">

            <div class="flex justify-between items-start">

                <div>

                    <h3 class="font-bold text-slate-800">
                        ${task.title}
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        ${project.name} • ${task.priority}
                    </p>

                </div>

                <button
                class="text-red-500 hover:text-red-700">
                    🗑
                </button>

            </div>

            <div class="flex gap-2 mt-4">

                <button
                onclick="changeStatus('${key}',${index},'todo')"
                class="${task.status=='todo' ? 'bg-yellow-500 text-white' : 'bg-slate-100'} px-3 py-2 rounded-xl text-sm">
                    📋 To Do
                </button>

                <button
                onclick="changeStatus('${key}',${index},'doing')"
                class="${task.status=='doing' ? 'bg-blue-500 text-white' : 'bg-slate-100'} px-3 py-2 rounded-xl text-sm">
                    ⚡ Doing
                </button>

                <button
                onclick="changeStatus('${key}',${index},'done')"
                class="${task.status=='done' ? 'bg-green-500 text-white' : 'bg-slate-100'} px-3 py-2 rounded-xl text-sm">
                    ✅ Done
                </button>

            </div>

        </div>
        `;

    });

    document.getElementById('taskContainer').innerHTML =
        html;

    document.getElementById('projectModal')
        .classList.remove('hidden');
}

function closeProjectModal(){

    document.getElementById('projectModal')
        .classList.add('hidden');
}

function changeStatus(project,index,status){

    projects[project].tasks[index].status = status;

    openProjectModal(project);
}

</script>

@endsection
