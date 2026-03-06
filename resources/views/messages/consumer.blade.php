<x-layouts::app title="Messages">
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 h-[calc(100vh-4rem)]">
    <div class="h-full flex flex-col pt-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
            <p class="mt-1 text-sm text-gray-500">Communicate with the admin</p>
        </div>

        <div class="bg-white shadow rounded-lg flex-1 mb-8 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 bg-white shadow-sm flex-shrink-0 flex items-center space-x-3">
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                    A
                </div>
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Admin</h2>
                </div>
            </div>
            
            <div class="flex-1 p-4 overflow-y-auto bg-gray-50" id="messages_container">
                @foreach($messages as $msg)
                    <div class="mb-4 flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs px-4 py-2 rounded-lg {{ $msg->sender_id === auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-900 rounded-bl-none' }}">
                            <p class="text-sm">{{ $msg->message }}</p>
                            <p class="text-[10px] mt-1 {{ $msg->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-400' }}">{{ $msg->created_at->format('M d, H:i') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 bg-white border-t border-gray-200 flex-shrink-0">
                <form action="{{ route('messages.store') }}" method="POST" class="flex space-x-2">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $admin->id ?? '' }}">
                    <input type="text" name="message" class="flex-1 focus:ring-blue-500 focus:border-blue-500 block w-full rounded-md sm:text-sm border-gray-300 px-4 py-2 border" placeholder="Type a message..." required>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById('messages_container');
        container.scrollTop = container.scrollHeight;
    });
</script>
</x-layouts::app>
