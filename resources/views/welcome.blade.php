<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->

    </head>
    <body>
    <div class="container mt-5">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    @endif
        <!-- Customer Information Form -->
        <form id="billingForm" action="{{route('create-order')}}" method="post">
            @csrf
            <div class="customer-details p-4 border rounded my-2">
                <h2>Billing Information</h2>
                <div class="form-group row">
                    <div class="col-2"><label for="customerName">Customer Name:</label></div>
                    <div class="col-4"><input type="text" class="form-control" name="name" placeholder="Enter customer name" required></div>
                </div>

                <div class="form-group row">
                    <div class="col-2"><label for="customerEmail">Customer Email:</label></div>
                    <div class="col-4"><input type="email" class="form-control" name="email" placeholder="Enter customer email" required></div>
                </div>

                <div class="form-group row">
                    <div class="col-2"><label for="contactNo">Contact No:</label></div>
                    <div class="col-4"><input type="tel" class="form-control" name="contactNo" placeholder="Enter contact number" required pattern="/[6-9]{1}[0-9]{9}/" oninput="this.value = this.value.replace(/[^\d]/g, '');"></div>
                </div>

                <div class="form-group row">
                    <div class="col-2"><label for="city">City:</label></div>
                    <div class="col-2">
                        <select class="form-control" required name="city">
                            @foreach($cities as $city)
                                <option value="{{$city['city_name']}}">{{$city['city_name']}}</option>
                            @endforeach
                        </select>
                    </div>


                </div>
            </div>

            <div class="billing-summary border rounded p-4 my-2">
                <!-- Billing Summary Section -->
                <h3>Billing Summary</h3>

                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="billingTableBody">
                    <!-- Billing rows will be added dynamically here -->
                    <tr>
                        <td>#</td>
                        <td><input type="text" class="form-control" id="product_desc" placeholder="Enter product name" oninput="this.value = this.value.replace(/[^\w\d\s]/g, '').replace(/(\..*?)\..*/g, '$1');"></td>
                        <td><input type="number" class="form-control" id="quantity" placeholder="Enter quantity" min="1" max="10" oninput="this.value = this.value.replace(/[^\d.]/g, '').replace(/(\..*?)\..*/g, '$1');"></td>
                        <td><input type="number" class="form-control" id="price" placeholder="Enter price" min="0" max="10000" step="0.1" oninput="this.value = this.value.replace(/[^\d.]/g, '').replace(/(\..*?)\..*/g, '$1');;"></td>
                        <td><button type="button" class="btn btn-primary" onclick="addBillingRow()">Add Product</button></td>
                    </tr>
                    </tbody>
                </table>

                <hr>

                <div class="row">
                    <div class="col-md-4 offset-md-7">
                        <h4>Summary:</h4>
                        <div class="row">
                            <div class="col-4"><p>Subtotal:</p></div>
                            <div class="col-8 text-right pr-4">
                                <p><span id="subtotal">0.00</span></p>
                                <input type="hidden" name="sub_total" value="0"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4"><p>Discount:</p></div>
                            <div class="col-8 "> <input type="text" id="discount" name="discount" class="form-control text-right" min="0" value="0" placeholder="Enter Discount" onchange="updateSummary()" autocomplete="false"></div>
                        </div>

                        <div class="row">
                            <div class="col-4"><p>GST: </p></div>
                            <div class="col-8 text-right pr-4">
                                <span id="gst">0.00</span>
                                <input type="hidden" name="gst" value="0"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6"><h4>Grand Total:</h4></div>
                            <div class="col-6 text-right pr-4">
                                <h4><span id="grandTotal" name="grandTotal">0.00</span></h4>
                                <input type="hidden" name="grand_total" value="0"/>
                            </div>
                        </div>

                    </div>
                </div>

                <button type="button" id="submitForm" class="btn btn-success">Generate Invoice</button>
            </div>

        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        var summary = [];

        function addBillingRow() {
            if($("#product_desc").val() == '' || $("#quantity").val() == "" || $("#price").val() == ""){
                return alert("Fill all details")
            }

            if($("#quantity").val() == 0 || $("#price").val() == 0){
                return alert("Qty or price can't be 0");
            }

            [product_desc, qty, price, total] = getValues();
            summary.push({product_desc, qty, price, total});

            renderRow();
            resetValues();
            updateSummary();
        }

        function getValues(){
            return [
                $("#product_desc").val(),
                $("#quantity").val(),
                $("#price").val(),
                $("#quantity").val() * $("#price").val()
            ];
        }

        function resetValues(){
            $("#product_desc").val("");
            $("#quantity").val("");
            $("#price").val("");

            $("#product_desc").focus();
        }

        function renderRow(){
            $("#billingTableBody tr:not(:first)").remove();

            var newRow = '';
            summary.forEach((data, i) => {
                newRow += '<tr>' +
                    '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeBillingRow(this, '+ i +')">X</button></td>' +
                    '<td><input type="hidden" readonly class="form-control" name="summary['+i+'][productName]"  value="'+data.product_desc+'"> ' + data.product_desc + '</td>' +
                    '<td><input type="hidden" readonly class="form-control" name="summary['+i+'][quantity]" value="'+data.qty+'">'+ data.qty +'</td>' +
                    '<td><input type="hidden" readonly class="form-control" name="summary['+i+'][price]" value="'+data.price+'">' + data.price +'</td>' +
                    '<td><input type="hidden" readonly class="form-control" name="summary['+i+'][total]" value="'+data.price * data.qty+'">' + data.price * data.qty +'</td>' +
                    '</tr>';
            })
            // var newRow = '<tr>' +
            //     '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeBillingRow(this, '+ counter +')">X</button></td>' +
            //     '<td><input type="hidden" readonly class="form-control" name="productName[]"  value="'+data.product_desc+'"> ' + data.product_desc + '</td>' +
            //     '<td><input type="hidden" readonly class="form-control" name="quantity[]" value="'+data.qty+'">'+ data.qty +'</td>' +
            //     '<td><input type="hidden" readonly class="form-control" name="price[]" value="'+data.price+'">' + data.price +'</td>' +
            //     '<td><input type="hidden" readonly class="form-control" name="total[]" value="'+data.price * data.qty+'">' + data.price * data.qty +'</td>' +
            //     '</tr>';


            $("#billingTableBody").append(newRow);
        }

        function removeBillingRow(button, index) {
            $(button).closest('tr').remove();
            summary.splice(index, 1);

            renderRow();
            updateSummary();
        }

        function updateSummary() {
            var subtotal = 0;
            summary.forEach(function(item) {
                subtotal += parseFloat(item.total) || 0;
            });

            var discount = parseFloat($("#discount").val()) || 0; // Add discount logic if needed
            var gst = 0.18 * subtotal; // Assuming 18% GST

            var grandTotal = subtotal - discount + gst;
            grandTotal = grandTotal > 0 ? grandTotal : 0

            $("#subtotal").text(subtotal.toFixed(2));
            $("#discount").text(discount.toFixed(2));
            $("#gst").text(gst.toFixed(2));
            $("#grandTotal").text(grandTotal.toFixed(2));

            $("input[name='gst']").val(gst.toFixed(2));
            $("input[name='sub_total']").val(subtotal.toFixed(2));
            $("input[name='grand_total']").val(grandTotal.toFixed(2));
        }

        $(document).ready(function() {
            $("#submitForm").click(function(e) {

                if(summary.length <= 0 ){
                    return alert("Add billing summary!");
                }

                $("#billingForm").submit();
            });
        });
    </script>
    </body>
</html>
