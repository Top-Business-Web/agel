<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');

    p, h3 {
        font-family: "Cairo", sans-serif;

    }
</style>

<div
    style="height: 100vh; display: flex; justify-content: center; align-items: center; flex-direction: column; direction: rtl;">
    <div class="container">
        <div style="box-shadow: 0 5px 83px 0 rgba(40, 40, 40, 0.11); padding: 20px; border-radius: 10px; width: 400px;">
            <h3 style="text-align: center;">المخزون الخاص {{ $investor->name }}</h3>
            <div style="display: flex">
                <p>الكمية المضافه</p>
                <p style="font-weight: bold; margin-right: 10px;">{{$stocksWithTheSameCategoryInAddOperation->sum('quantity')}}</p>

            </div>
            <div style="margin-right: 20px;">
                @foreach($stocksWithTheSameCategoryInAddOperation as $stock)
                    <li class="fa fa-arrow-up" style="color: #151515;">{{$stock->quantity}} من {{$stock->category->name}}</li>
                @endforeach
            </div>


            <div style="display: flex">
                <p>السعر الاحمالي للكميه المضافه</p>
                <p style="font-weight: bold; margin-right: 10px;">{{$stocksWithTheSameCategoryInAddOperation->sum('total_price_add')}}</p>
            </div>
            <div style="display: flex">
                <p>الكمية المنقصه</p>
                <p style="font-weight: bold; margin-right: 10px;">{{$stocksWithTheSameCategoryInSellOperation->sum('quantity')}}</p>
            </div>
            <div style="display: flex">
                <p>السعر الاحمالي للكميه المنقصه</p>
                <p style="font-weight: bold; margin-right: 10px;">{{$stocksWithTheSameCategoryInSellOperation->sum('total_price_sub')}}</p>
            </div>
            <hr>
            <div style="display: flex">
                <p>الكميه المتبقيه</p>
                <p style="font-weight: bold; margin-right: 10px;">{{$stocksWithTheSameCategoryInAddOperation->sum('quantity') - $stocksWithTheSameCategoryInSellOperation->sum('quantity')}}</p>
            </div>
        </div>
    </div>
</div>

