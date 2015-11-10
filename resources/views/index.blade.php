{{--@foreach--}}
<ul>
    @foreach($items as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>

{{--@if--}}
{{--@if($itemCount = count($items))--}}
    {{--<p>There are {{ $itemCount }} items !</p>--}}
{{--@else--}}
    {{--<p>There is no item !</p>--}}
{{--@endif--}}

{{--@forelse--}}
{{--@forelse($items as $item)--}}
    {{--<p>The item is {{ $item }}</p>--}}
{{--@empty--}}
    {{--<p>There is no item !</p>--}}
{{--@endforelse--}}