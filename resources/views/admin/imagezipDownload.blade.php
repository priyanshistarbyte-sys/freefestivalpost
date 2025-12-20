@extends('layouts.main')
@section('title', '')
@section('content')
<div class="row g-4">

    <!-- Images Copy With Zip Download -->
    <div class="col-lg-6 col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Images Copy With Zip Download</h5>
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>

            <div class="card-body">
                <form action="{{ route('image-zip.download.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="sub_category_id" class="form-label fw-semibold">Category</label>
                        <select class="form-select" name="sub_category_id" id="sub_category_id" required>
                            <option value="">-- Select Category --</option>
                            <option value="all">All</option>
                                @foreach ($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->mtitle }}
                                    </option>
                                @endforeach
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            OK
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Plan Images Upload -->
    <div class="col-lg-6 col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Plan Images Upload</h5>
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>

            <div class="card-body text-muted text-center py-5">
                Coming Soon...
            </div>
        </div>
    </div>

</div>
@endsection


@push('scripts')
<script>
    // Toggle card collapse function
        function toggleCard(button) {
            const card = $(button).closest('.card');
            const cardBody = card.find('.card-body');
            const icon = $(button).find('i');

            cardBody.slideToggle();
            icon.toggleClass('fa-minus fa-plus');
        }
</script>
@endpush
