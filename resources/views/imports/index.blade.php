@extends('layouts.admin')

@section('title', 'Import Excel')
@section('page_title', 'Import Excel')

@section('content')
<div class="space-y-6">

    <!-- Section Header -->
    <div>
        <h2 class="text-xl font-semibold text-slate-200">Import Members from Excel</h2>
        <p class="text-xs text-slate-400 mt-1">Upload a spreadsheet containing BNI member information. Supported formats: .xlsx, .xls, .csv.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Upload Form Card -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800/80 rounded-2xl shadow-lg p-8">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-6 pb-2 border-b border-slate-850">Select File</h3>

            <form action="{{ route('admin.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- File input area -->
                <div class="relative border-2 border-dashed border-slate-800 hover:border-brand/50 rounded-2xl p-8 bg-slate-950/40 text-center transition-colors group cursor-pointer">
                    <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    <div class="flex flex-col items-center justify-center space-y-3">
                        <div class="p-4 bg-slate-900 text-brand-light rounded-2xl border border-slate-800 shadow-inner group-hover:scale-105 transition-transform duration-200">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-slate-200 group-hover:text-brand-light transition-colors">Click to upload</span>
                            <span class="text-sm text-slate-500"> or drag and drop</span>
                        </div>
                        <p class="text-xs text-slate-500">XLSX, XLS, or CSV files up to 10MB</p>
                    </div>
                </div>

                <!-- Display selected filename dynamically -->
                <div id="file-details" class="hidden p-4 rounded-xl bg-slate-950/60 border border-slate-850 flex items-center justify-between text-sm">
                    <div class="flex items-center space-x-3 text-slate-300">
                        <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span id="file-name" class="font-medium truncate max-w-xs">filename.csv</span>
                        <span id="file-size" class="text-xs text-slate-500">Size</span>
                    </div>
                    <button type="button" id="remove-file" class="text-xs font-semibold text-rose-400 hover:text-rose-350 transition">
                        Remove
                    </button>
                </div>

                <!-- Submit buttons -->
                <div class="pt-4 border-t border-slate-850 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border border-slate-800 hover:border-slate-700 text-slate-300 rounded-xl text-sm font-semibold transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-semibold shadow-md shadow-brand/20 transition cursor-pointer">
                        Start Upload
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Side: Guidelines Info -->
        <div class="space-y-6">
            <!-- Guidelines card -->
            <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-md">
                <h4 class="text-sm font-semibold text-slate-300 mb-4">Guidelines & Best Practices</h4>
                <ul class="text-xs text-slate-400 space-y-3 list-disc pl-4">
                    <li>The Excel sheet <strong class="text-slate-300">MUST</strong> contain headings in the first row.</li>
                    <li>Required headers are: <code class="text-brand-light font-semibold px-1 rounded">name</code> and <code class="text-brand-light font-semibold px-1 rounded">email</code>.</li>
                    <li>Optional columns include: <code class="text-slate-300 font-semibold">phone</code>, <code class="text-slate-300 font-semibold">company</code>, <code class="text-slate-300 font-semibold">chapter</code>, <code class="text-slate-300 font-semibold">designation</code>, <code class="text-slate-300 font-semibold">status</code>.</li>
                    <li>Records with duplicate email addresses (existing in the database or duplicated inside the uploaded file) will be <strong class="text-amber-400">skipped automatically</strong>.</li>
                    <li>The status column accepts <code class="text-slate-300">active</code> or <code class="text-slate-300">inactive</code>. Defaults to <code class="text-slate-300">active</code> if left empty.</li>
                </ul>
                
                <div class="mt-6 pt-6 border-t border-slate-850">
                    <p class="text-xs text-slate-500 mb-3">Download the sample template for correct formatting:</p>
                    <a href="{{ route('admin.export-template') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-xs font-semibold text-brand-light hover:text-brand-light bg-slate-950/60 border border-slate-800 hover:border-slate-700 rounded-xl transition duration-200">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Template CSV
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- JavaScript to show file details after selecting -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file');
        const fileDetails = document.getElementById('file-details');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const removeFile = document.getElementById('remove-file');

        fileInput.addEventListener('change', function(e) {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                fileName.textContent = file.name;
                
                // Format file size
                let sizeStr = '';
                if (file.size < 1024 * 1024) {
                    sizeStr = (file.size / 1024).toFixed(1) + ' KB';
                } else {
                    sizeStr = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                }
                fileSize.textContent = sizeStr;

                fileDetails.classList.remove('hidden');
            }
        });

        removeFile.addEventListener('click', function() {
            fileInput.value = '';
            fileDetails.classList.add('hidden');
        });
    });
</script>
@endsection