<h3>المخزون الخاص {{ $investor->name }}</h3>
<label>الكمية المضافه</label>
<h3>{{$stocksWithTheSameCategoryInAddOperation->sum('quantity')}}</h3>
<label>السعر الاحمالي للكميه المضافه</label>
<h3>{{$stocksWithTheSameCategoryInAddOperation->sum('total_price_add')}}</h3>
<label>الكمية المنقصه</label>
<h3>{{$stocksWithTheSameCategoryInSellOperation->sum('quantity')}}</h3>
<label>السعر الاحمالي للكميه المنقصه</label>
<h3>{{$stocksWithTheSameCategoryInSellOperation->sum('total_price_sub')}}</h3>
<label>الكميه المتبقيه</label>
<h3>{{$stocksWithTheSameCategoryInAddOperation->sum('quantity') - $stocksWithTheSameCategoryInSellOperation->sum('quantity')}}</h3>

