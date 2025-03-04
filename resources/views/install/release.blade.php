@extends('backend.layouts.master')
@section('installed_addon')
    active
@endsection
@section('addon_utility')
    active
@endsection
@section('title')
    {{ __('create_release') }}
@endsection
@section('content')
    <section class="section">
        <div class="row d-flex justify-content-center">
            <div class="col-sm-xs-12 col-md-5">
                <div class="section-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-block">
                            <h2 class="section-title">{{ __('create_release') }}</h2>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header input-title">
                        <h4>{{ __('install_update') }}</h4>
                    </div>
                    <div class="card-body card-body-paddding">
                        <form method="POST" action="{{ route('create.release') }}">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="prefix">{{ __('prefix_or_system_name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="prefix" id="prefix"
                                       placeholder="i.e SaleBot"
                                       value="{{ old('prefix') }}" class="form-control" required>
                                @if ($errors->has('prefix'))
                                    <div class="invalid-feedback">
                                        <p>{{ $errors->first('prefix') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group mb-4">
                                <label for="last_version">{{ __('latest_version') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="latest_version" id="latest_version"
                                       placeholder="{{ __('enter_latest_version')  }}"
                                       value="{{ old('latest_version') }}" class="form-control" required>
                                @if ($errors->has('last_version'))
                                    <div class="invalid-feedback">
                                        <p>{{ $errors->first('latest_version') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group mb-4">
                                <label for="version">{{ __('version_to_be_created') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="version" id="version"
                                       placeholder="{{ __('enter_version')  }}"
                                       value="{{ old('version') }}" class="form-control" required>
                                @if ($errors->has('version'))
                                    <div class="invalid-feedback">
                                        <p>{{ $errors->first('version') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group mb-4">
                                <label for="latest_commit">{{ __('latest_commit') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="latest_commit" id="latest_commit"
                                       placeholder="{{ __('enter_latest_commit')  }}"
                                       value="{{ old('latest_commit') }}" class="form-control" required>
                                @if ($errors->has('latest_commit'))
                                    <div class="invalid-feedback">
                                        <p>{{ $errors->first('latest_commit') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group mb-4">
                                <label for="old_commit">{{ __('old_commit') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="old_commit" id="old_commit"
                                       placeholder="{{ __('enter_latest_commit')  }}"
                                       value="{{ old('old_commit') }}" class="form-control" required>
                                @if ($errors->has('old_commit'))
                                    <div class="invalid-feedback">
                                        <p>{{ $errors->first('old_commit') }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-outline-primary" tabindex="4">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

