<?php
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.admin')] class extends Component {
    // Logika backend bisa ditambahkan di sini nanti
}; ?>

<div class="max-w-7xl mx-auto space-y-8">
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                </div>
                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full">+4%</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Projects</p>
                <h3 class="text-3xl font-extrabold text-slate-900">24</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <span class="px-2 py-1 bg-orange-50 text-orange-600 text-xs font-semibold rounded-full">+12%</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Tasks</p>
                <h3 class="text-3xl font-extrabold text-slate-900">142</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full">69% rate</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tasks Completed</p>
                <h3 class="text-3xl font-extrabold text-slate-900">98</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="px-2 py-1 bg-red-50 text-red-600 text-xs font-semibold rounded-full">Urgent</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tasks Overdue</p>
                <h3 class="text-3xl font-extrabold text-slate-900">12</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-slate-900">Active Projects</h2>
                <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-800">View All Archive</a>
            </div>

            <div class="bg-white rounded-2xl p-5 flex items-center border border-slate-100 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 font-bold flex items-center justify-center mr-4">NC</div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-900">NeoCore Cloud Infrastructure</h4>
                    <p class="text-xs text-slate-500 mt-1">Updated 2 hours ago</p>
                </div>
                <div class="w-32 mx-6 hidden md:block">
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-700">Progress</span>
                        <span class="text-blue-600">82%</span>
                    </div>
                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600 rounded-full" style="width: 82%"></div>
                    </div>
                </div>
                <div class="text-right mr-6">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tasks</p>
                    <p class="text-sm font-bold text-slate-900">45 / <span class="text-slate-400">55</span></p>
                </div>
                <button class="px-4 py-2 text-sm font-medium text-blue-600 border border-slate-200 rounded-lg hover:bg-slate-50">View Details</button>
            </div>

            <div class="bg-white rounded-2xl p-5 flex items-center border border-slate-100 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-700 font-bold flex items-center justify-center mr-4">AR</div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-900">Artemis Retail App</h4>
                    <p class="text-xs text-slate-500 mt-1">Updated 5 hours ago</p>
                </div>
                <div class="w-32 mx-6 hidden md:block">
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-700">Progress</span>
                        <span class="text-orange-600">45%</span>
                    </div>
                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-orange-500 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div class="text-right mr-6">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tasks</p>
                    <p class="text-sm font-bold text-slate-900">12 / <span class="text-slate-400">28</span></p>
                </div>
                <button class="px-4 py-2 text-sm font-medium text-blue-600 border border-slate-200 rounded-lg hover:bg-slate-50">View Details</button>
            </div>
            
            <div class="bg-white rounded-2xl p-5 flex items-center border border-slate-100 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 font-bold flex items-center justify-center mr-4">UX</div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-900">UX Audit - Client Portal</h4>
                    <p class="text-xs text-slate-500 mt-1">Updated Yesterday</p>
                </div>
                <div class="w-32 mx-6 hidden md:block">
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-slate-700">Progress</span>
                        <span class="text-blue-500">95%</span>
                    </div>
                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-400 rounded-full" style="width: 95%"></div>
                    </div>
                </div>
                <div class="text-right mr-6">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tasks</p>
                    <p class="text-sm font-bold text-slate-900">38 / <span class="text-slate-400">40</span></p>
                </div>
                <button class="px-4 py-2 text-sm font-medium text-blue-600 border border-slate-200 rounded-lg hover:bg-slate-50">View Details</button>
            </div>
        </div>

        <div class="space-y-8">
            
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-6">Quick Actions</h2>
                <div class="space-y-4">
                    <button class="w-full flex items-center p-4 bg-blue-700 hover:bg-blue-800 text-white rounded-2xl shadow-sm transition-colors">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span class="font-bold">Create Project</span>
                    </button>
                    
                    <button class="w-full flex items-center p-4 bg-white hover:bg-slate-50 text-slate-800 border border-slate-100 rounded-2xl shadow-sm transition-colors">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="font-bold">Create New Task</span>
                    </button>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-slate-900">Board Preview</h2>
                    <button class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-slate-100/50 rounded-xl p-3">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="text-xs font-bold text-slate-700 uppercase">To Do</span>
                            <span class="w-5 h-5 rounded-full bg-white text-slate-600 text-[10px] font-bold flex items-center justify-center">3</span>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border border-slate-100 mb-2">
                            <p class="text-xs font-bold text-slate-800 mb-2">Define API specs</p>
                            <div class="flex justify-between items-center">
                                <img src="https://ui-avatars.com/api/?name=R&background=random" class="w-5 h-5 rounded-full">
                                <span class="px-1.5 py-0.5 bg-orange-100 text-orange-700 text-[10px] font-bold rounded">High</span>
                            </div>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border border-slate-100">
                            <p class="text-xs font-bold text-slate-800">Design Audit</p>
                        </div>
                    </div>

                    <div class="bg-slate-100/50 rounded-xl p-3">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="text-xs font-bold text-slate-700 uppercase">Doing</span>
                            <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center">2</span>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border border-slate-100 border-l-2 border-l-blue-500">
                            <p class="text-xs font-bold text-slate-800 mb-2">Sprint Planning</p>
                            <img src="https://ui-avatars.com/api/?name=A&background=random" class="w-5 h-5 rounded-full">
                        </div>
                    </div>

                    <div class="bg-slate-100/50 rounded-xl p-3 opacity-60">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase">Done</span>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border border-slate-100 line-through text-slate-400">
                            <p class="text-xs font-bold">QA Testing</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>