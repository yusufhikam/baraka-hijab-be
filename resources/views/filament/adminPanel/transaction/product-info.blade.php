<div class="flex flex-col gap-2 text-sm p-2">
@foreach ($getRecord()->transactionItems as $items)
    <div class="flex flex-col">
        <p class="font-bold">{{$items->productVariant->product->name}}</p>
        <p class="text-xs text-gray-400">[ctg: {{$items->productVariant->product->subCategory->category->name}}]</p>
    </div>
    <div class="text-xs rounded border p-1" >
        <p>Variants: </p>
        <p class="ps-2 flex items-center gap-2">• Color: <span class="border" style="display:inline-block; background-color: {{$items->productVariant->color}}; width: 1rem; height:1rem; border-radius:100%;"></span></p>
        <p class="ps-2">• Size : <span>{{$items->productVariant->size}}</span></p>
    </div>
@endforeach
</div>