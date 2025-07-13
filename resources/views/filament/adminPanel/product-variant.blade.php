<style>
.variant-info:hover{
    background-color: #c1c1c134;
    transition: all 400ms ease-in-out;
}

.is_ready{
    color: rgb(129, 255, 129);
    background-color:green;
    text-align: center;
}

.is_not_ready{
    color: rgb(255, 0, 0);
    background-color: rgba(255, 0, 0, 0.337);
}

</style>


<div class="flex flex-col items-center">
    @foreach ($getRecord()->productVariants as $variant)
        <div class="flex items-center gap-2 text-sm variant-info p-2 w-full">
            <span class="inline-block w-8 h-8 rounded-full border" style="background-color: {{ $variant->color }}"></span>
            <div class="px-2 flex flex-col gap-2">
                <p class="text-red-500">
                    Stock: <span class="font-bold">{{ $variant->stock }}</span>
                </p>
                <p>
                    Size: <span class="font-bold">{{ $variant->size }}</span>
                </p>
                <p>
                    Weight: <span class="font-bold">{{ $variant->weight }}gr</span>
                </p>
                <p class=" availability  ">
                    Status: <span class="px-1.5 mt-2 py-1 w-fit rounded {{$variant->is_ready ? 'is_ready' : 'is_not_ready'}}">{{ $variant->is_ready ? 'Available' : 'Not Available' }}</span>
                </p>
            </div>
        </div>
    @endforeach
</div>
