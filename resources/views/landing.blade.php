@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-dark via-gray-900 to-dark text-light py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Welcome to <span class="text-primary">NasaTV</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto">
                Your premier subscription management platform for seamless service delivery and customer satisfaction.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="#plans" class="bg-primary hover:bg-yellow-500 text-dark font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
                    View Plans
                </a>
                <a href="{{ route('login') }}" class="bg-transparent border-2 border-primary hover:bg-primary hover:text-dark text-primary font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
                    Login
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Plans Section -->
<section id="plans" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h2>
            <p class="text-xl text-gray-600">Select the perfect subscription plan that fits your needs</p>
        </div>

        @if($plans->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-2">
                        <!-- Plan Header -->
                        <div class="bg-gradient-to-br from-dark to-gray-800 text-light p-8 text-center">
                            <h3 class="text-2xl font-bold mb-2">{{ $plan->name }}</h3>
                            <div class="mt-4">
                                <span class="text-5xl font-bold text-primary">${{ number_format($plan->price, 2) }}</span>
                            </div>
                            <p class="text-gray-300 mt-2">{{ $plan->duration }}</p>
                        </div>

                        <!-- Plan Body -->
                        <div class="p-8">
                            @if($plan->description)
                                <p class="text-gray-600 mb-6 text-center">{{ $plan->description }}</p>
                            @endif

                            <!-- Features List -->
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Full HD Quality
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    24/7 Support
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Multiple Devices
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    No Ads
                                </li>
                            </ul>

                            
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-xl text-gray-600">No plans available at the moment. Please check back soon!</p>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="bg-dark text-light py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-6">Ready to Get Started?</h2>
        <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
            Join NasaTV today and streamline your subscription management process.
        </p>
        
    </div>
</section>
@endsection
