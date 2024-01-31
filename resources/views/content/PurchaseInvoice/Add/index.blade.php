@inject('PurchaseInvoInvoice','App\Http\Controller','PurchaseInvoiceController')
@extends('adminlte::page')

@section('title','MOZAIC Minimarket')
@section('js')
<script>
    function function_elements_add(name, value){
        $.ajax({
            type: "POST",
            url:  "{{route('PI.add-elements')}}",
            data: {
                'name'  :name,
                'value' :value,
                '_token':'{{csrf_token()}}'
            },
            success: function(msg){

            }
        });
    }

    $(document).ready(function(){
        $("quantity").change(function(){
            var quantity = $("#quantity").val();
            var cost = $("#item_unit_cost").val();
            var subtotal = quantity * cost;

            $("#subtotal_amount").val(subtotal);
            $("#subtotal_amount_view").val(toRp(subtotal));
            $("#subtotal_amount_after_discount_view").val(toRp(subtotal));
            $("#subtotal_amount_after_discount").val(subtotal);
        });

        $('#item_price_new_view').change(function(){
            var price_new = parseInt($('#item_price_new_view').val());
            var cost_new = parseInt($('#item_cost_view').val());
            var price_new = parseInt($('#margin_percentage_old').val());
            var price_new = parseInt($('#margin_percentage').val());
            var margin_percentage = ((price_new - cost_new) /  cost_new) * 100;

                if(Number.isInteger(margin_percentage)){
                    $('#margin_percentage').val(margin_percentage);
                } else{
                    $('#margin_percentage').val(margin_percentage.toFixed(2));
                }
                $('#item_price_new_view').val(toRp(price_new));
                $('#item_price_new').val(price_new);
        });

        $('#margin_percentage').change(function(){
            var cost_new = parseInt($('#item_cost_new').val());
            var margin_old = parseInt($('#margin_Percentage_old').val());
            var margin = parseInt($('#margin_percentage').val());
            var price_new = ((margin * cost_new) / 100) +cost_new;

                if(Number.isInteger(margin)){
                    $('#margin_percentage').val(margin);
                } else{
                    $('#margin_percentage').val(margin.toFixed(2));
                }
                $('#item_price_new_view').val(toRp(price_new));
                $('#item_price_new').val(price_new);
        });

        $("#item_unit_cost_view").change(function(){
            var item_package_id     = $("#item_package_id").val();
            var cost_new            = $("#item_unit_cost_view").val();
            var cost                = $("item_unit_cost").val();
            $.ajax({
                url::"{{route(PI.index) }}"+'/'+item_package_id,
                type: "GET",
                dataType: 
            })
        })

    })
</script>
