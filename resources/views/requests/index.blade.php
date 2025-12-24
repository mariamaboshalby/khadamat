@extends('layouts.app')

@section('title', 'طلباتي')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">طلباتي</h1>
        <a href="{{ route('requests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
            إنشاء طلب جديد
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if($requests->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <h3 class="text-xl font-semibold text-gray-700 mb-2">لا توجد طلبات</h3>
            <p class="text-gray-500 mb-4">لم تقم بإنشاء أي طلبات بعد.</p>
            <a href="{{ route('requests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                إنشاء طلب جديد
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($requests as $request)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-semibold text-gray-800">{{ $request->service->name }}</h3>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($request->status === 'approved') bg-blue-100 text-blue-800
                                @elseif($request->status === 'in_progress') bg-purple-100 text-purple-800
                                @elseif($request->status === 'completed') bg-green-100 text-green-800
                                @elseif($request->status === 'cancelled') bg-red-100 text-red-800
                                @endif">
                                @if($request->status === 'pending') معلق
                                @elseif($request->status === 'approved') مقبول
                                @elseif($request->status === 'in_progress') قيد التنفيذ
                                @elseif($request->status === 'completed') مكتمل
                                @elseif($request->status === 'cancelled') ملغي
                                @endif
                            </span>
                        </div>
                        
                        <p class="text-gray-600 mb-4">{{ Str::limit($request->description, 100) }}</p>
                        
                        <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                            <span>{{ $request->created_at->format('Y-m-d') }}</span>
                            <span>{{ $request->address }}</span>
                        </div>
                        
                        @if($request->media->count() > 0)
                            <div class="mb-4">
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($request->media->take(3) as $media)
                                        <img src="{{ $media->getUrl() }}" alt="Request Image" class="rounded w-full h-20 object-cover">
                                    @endforeach
                                    @if($request->media->count() > 3)
                                        <div class="bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-600 text-sm">+{{ $request->media->count() - 3 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <a href="{{ route('requests.show', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                عرض التفاصيل
                            </a>
                            
                            @if($request->status === 'pending')
                                <a href="{{ route('requests.edit', \App\Helpers\EncryptionHelper::encryptId($request->id)) }}" class="text-gray-600 hover:text-gray-800 font-medium">
                                    تعديل
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection