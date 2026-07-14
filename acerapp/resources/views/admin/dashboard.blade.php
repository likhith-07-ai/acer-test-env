<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">KPI Dashboard</h1>

        <!-- KPI Group 1 - All Documents -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">All Documents</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-primary-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Documents</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $totalDocuments }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-secondary-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">SEBI Documents</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $sebiDocuments }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-tertiary-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">RBI Documents</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $rbiDocuments }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-quaternary-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Restricted Documents</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $restrictedDocuments }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Group 2 - SEBI -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">SEBI Documents</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total SEBI Documents</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalSebiDocuments }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Public SEBI Documents</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $publicSebiDocuments }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Restricted SEBI Documents</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $restrictedSebiDocuments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Group 3 - RBI -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">RBI Documents</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total RBI Documents</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalRbiDocuments }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Public RBI Documents</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $publicRbiDocuments }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Restricted RBI Documents</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $restrictedRbiDocuments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>