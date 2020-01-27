<?php

namespace App\Exports;

use App\Photo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PhotosExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Photo::all();
    }

    /**
     * @var Photo $photo
     */
    public function map($photo): array
    {
        $rows = array();

        foreach ($photo->posters as $poster) {
            $tags = $poster->tags->values();

            $rows[] = [
                $poster->id,
                $poster->text,
                $photo->city,
                $photo->date ? $photo->date->toDateString() : null,
                $poster->pivot->gender == 'male' ? 'Masculino' : 'Feminino',
                $poster->photographer,
                $tags->get(0) ? $tags->get(0)->text : null,
                $tags->get(1) ? $tags->get(1)->text : null,
                $tags->get(2) ? $tags->get(2)->text : null,
                $tags->get(3) ? $tags->get(3)->text : null,
                $tags->get(4) ? $tags->get(4)->text : null,
                url("/principal/foto/{$photo->id}"),
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'ID cartaz',
            'Texto',
            'Cidade',
            'Data',
            'Gênero',
            'Fotógrafo',
            'Tag 1',
            'Tag 2',
            'Tag 3',
            'Tag 4',
            'Tag 5',
            'Link',
        ];
    }
}
