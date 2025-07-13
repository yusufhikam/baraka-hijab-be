<div class="flex flex-col gap-2 text-sm p-1 ">
    <?php $customer = $getRecord()->user; ?>

    <p>Name : {{ $customer->name }}</p>
    <p>Email : {{$customer->email}}</p>
    <p>Phone : 0{{$customer->phone_number}}</p>
</div>
