<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title>Guest's Form</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card mt-5">
                    <h3 class="card-title text-center mt-5">
                        {{__('form.title')}}
                    </h3>
                    <div class="card-body">
                        <form>
                            @csrf
                            <div class="form-group">
                                <label>{{__('form.profile.name')}}</label>
                                <input type="text" class="form-control" name="">
                            </div>
                            <div class="form-group">
                                <label>{{__('form.profile.address')}}</label>
                                <input type="text" class="form-control" name="">
                            </div>
                            <div class="form-group">
                                <label>{{__('form.profile.age')}}</label>
                                <input type="text" class="form-control" name="">
                            </div>
                            <div class="form-group">
                                <label>{{__('form.profile.email')}}</label>
                                <input type="text" class="form-control" name="">
                            </div>
                            <div>
                                <label>{{__('form.thank')}}</label>
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('form.button')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>

</html>