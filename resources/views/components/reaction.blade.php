
    @auth
        <div class="like">

            <!-- LIKE -->
            <form method="POST" action="{{ route('articles.react', [$article, 'like']) }}">
                @csrf
                <button
                        class="px-4 py-1 rounded transition
                       {{ $reaction === 'like'
                            ? 'bg-green-600 text-white'
                            : 'bg-gray-200' }}">
                    <!-- {{ $reaction === 'like' ? 'Liker' : 'Like' }}  -->
                    <i class='bx bx-like'></i>
                </button>
            </form>
            <!-- DISLIKE -->
            <form method="POST" action="{{ route('articles.react', [$article, 'dislike']) }}">
                @csrf
                <button
                        class="px-4 py-1 rounded transition
                       {{ $reaction === 'dislike'
                            ? 'bg-red-600 text-white'
                            : 'bg-gray-200' }}">
                    <!-- {{ $reaction === 'dislike' ? 'Disliker' : 'Dislike' }} -->
                    <i class='bx bx-dislike' ></i>
                </button>
            </form>

        </div>
    @endauth