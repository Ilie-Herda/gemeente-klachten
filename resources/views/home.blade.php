<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome to the Municipal Complaint Portal') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <p class="text-gray-700">
                This website allows citizens to report issues in their local environment,
                such as broken streetlights, fallen trees, litter, or other problems in public spaces.
            </p>

            <p class="text-gray-700">
                Your report will be received by the municipality, who will review and handle the complaint
                as soon as possible. Please provide clear information about the location and nature of the problem.
            </p>

            <ul class="list-disc list-inside text-gray-700">
                <li>Quickly submit a complaint using an online form</li>
                <li>Attach a photo to better illustrate the problem</li>
                <li>Automatically share your GPS location (with permission)</li>
                <li>Municipal staff can view and manage all complaints in an admin dashboard</li>
            </ul>

            <div class="pt-4">
                <a href="{{ route('complaints.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Submit a complaint
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
