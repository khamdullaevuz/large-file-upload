<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Upload file') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div x-data="uploader" class="p-6">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%" id="progress"></div>
                    </div>
                    <p class="text-gray-900 dark:text-gray-100">{{ __("Upload File") }}</p>
                    <input type="file" x-ref="file">
                    <button x-on:click="upload">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('uploader', () => ({
            upload()
            {
                const file = this.$refs.file.files[0]
                var r = new Resumable({
                    headers: {
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    target: 'upload',
                    chunkSize: 1 * 1024 * 1024,
                    testChunks: false
                })

                r.addFile(file)

                r.on('fileProgress', (file) => {
                    const progress = (file.progress() * 100).toFixed(2)
                    document.getElementById('progress').style.width = `${progress}%`
                })

                r.on('fileAdded', (file, event) => {
                    r.upload()
                })
            }
        }))
    })
</script>
</x-app-layout>
