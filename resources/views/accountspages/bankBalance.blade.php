@extends('layouts.navigation')
@section('bank_balance','active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;
    
    ?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div style="padding: 10px;">
                            <div class="card-header-bank">
                                <h4 class="header ">Bank Balance</h4>
                            </div>
                        </div>

                        <div class="card-body">
                       

                                <form action="/bank-balance-filter" method="post" class="needs-validation" novalidate="">
                                    @csrf

                                    <div class="row">
                                       <div class="col-4">
                                            <div class="form-group">
                                                <label>Account No</label>
                                                <select class="form-control" name="bank_id"  required onchange="window.location.assign('/bank-balance-show?bank_id=' + this.value)">
                                                    <option value=""  selected>All</option>
                                                    @foreach ($accountBanks as $accountBank)
                                                        <option value="{{ $accountBank->id }}"  {{$bankId==$accountBank->id?'selected':''}}>{{ $accountBank->account_no }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">@error('product_cat_id')
                                                        {{ $message }}@enderror</span>
                                                        
                                            </div>
                                        </div>                            
                                    </div>                                    
                                </form>


                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">

                                        <thead>
                                            <tr>
                                                <th style="display: none"></th>
                                                <th>Date</th>
                                                <th>Account No</th>
                                                <th>Credit</th>
                                                <th>Debit</th>
                                                <th>Balance</th>
                                                <th>Details</th>

                                            </tr>
                                        </thead>


                                        <tbody>
                                            @foreach ($accountBankBalances as $accountBankBalances)
                                                <tr>
                                                    <td style="display: none">#</td>
                                                    <td>{{ $accountBankBalances->date }}</td>
                                                    <td>{{ $accountBankBalances->account_no }}</td>
                                                    <td>{{ $accountBankBalances->credit }}</td>
                                                    <td>{{ $accountBankBalances->debit }}</td>
                                                    <td>{{ $accountBankBalances->balance }}</td>
                                                    <td>{{ $accountBankBalances->details }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endsection
