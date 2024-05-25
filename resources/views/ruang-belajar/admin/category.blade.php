@extends('layout.rubick')
@section('title', 'Dashboard')

@section('content')

    <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
        <thead class="table-dark font-bold">
            <tr>
                <th>No.</th>
                <th>Kategori</th>
                <th>Icon</th>
                <th>Aksi</th>

            </tr>
        </thead>
        <tbody>
        <tbody>
            @php
                $no = 0;
            @endphp
            @foreach ($categories as $category)
                @php
                    $no++;
                @endphp
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $category->name }}</td>
                    <td class="text-center">
                        <img src="{{ URL::to('/' . $category->icon) }}">
                    </td>
                    <td>
                        <a href="{{ route('ruang-belajar.admin-category-edit', $category->id) }}" class="btn btn-primary"><i
                                class="fas fa-edit"></i>Edit</a>
                        <a href="" class="btn btn-danger delete-item"><i class="fas fa-trash-alt"></i>Delete</a>

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
