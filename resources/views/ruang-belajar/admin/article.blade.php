@extends('layout.rubick')
@section('title', 'Dashboard')

@section('content')

    <table id="perencanaan" class="table table-bordered table-striped mt-5" cellspacing="0" width="100%">
        <thead class="table-dark font-bold">
            <tr>
                <th>No.</th>
                <th class="w-40">Foto</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>

            </tr>
        </thead>
        <tbody>
        <tbody>
            @php
                $no = 0;
            @endphp
            @foreach ($articles as $article)
                @php
                    $no++;
                @endphp
                <tr>
                    <td>{{ $no }}</td>
                    <td><img src="{{ URL::to('/' . $article->image) }}"></td>
                    <td>{{ $article->title }}</td>
                    <td class="text-center">
                        {{ $article->category->name }}
                    </td>
                    <td></td>
                    <td>
                        <a href="{{ route('ruang-belajar.admin-category-edit', $article->id) }}" class="btn btn-primary"><i
                                class="fas fa-edit"></i>Edit</a>
                        <a href="" class="btn btn-danger delete-item"><i class="fas fa-trash-alt"></i>Delete</a>

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
