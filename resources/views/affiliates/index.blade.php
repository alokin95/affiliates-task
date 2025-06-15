@php
    use App\DTOs\AffiliateDTO;
    use Illuminate\Pagination\LengthAwarePaginator;
@endphp
@php
    /** @var LengthAwarePaginator<int, AffiliateDTO> $paginator */
    /** @var AffiliateDTO $affiliate */
@endphp

@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight mb-6">Nearby Affiliates (100km)</h1>

        @if($paginator->isEmpty())
            <p class="text-gray-600">No affiliates within the configured radius.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow rounded-lg">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Latitude</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Longitude</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @foreach ($paginator as $affiliate)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $affiliate->affiliateId }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $affiliate->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $affiliate->latitude }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $affiliate->longitude }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $paginator->links() }}
            </div>
        @endif
    </div>
@endsection
