@props(['comments'])

<div {{ $attributes }}>
    @foreach ($comments as $comment)
        <livewire:comment.show :comment="$comment" :key="$comment['id']" />
    @endforeach
</div>