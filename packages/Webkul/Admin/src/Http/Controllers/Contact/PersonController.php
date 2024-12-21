<?php

namespace Webkul\Admin\Http\Controllers\Contact;

use App\Imports\PersonsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Maatwebsite\Excel\Facades\Excel;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Attribute\Http\Requests\AttributeForm;
use Webkul\Contact\Repositories\PersonRepository;

class PersonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PersonRepository $personRepository)
    {
        request()->request->add(['entity_type' => 'persons']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(\Webkul\Admin\DataGrids\Contact\PersonDataGrid::class)->toJson();
        }

        return view('admin::contacts.persons.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::contacts.persons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Webkul\Attribute\Http\Requests\AttributeForm $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeForm $request)
    {
        Event::dispatch('contacts.person.create.before');

        $person = $this->personRepository->create($this->sanitizeRequestedPersonData());

        Event::dispatch('contacts.person.create.after', $person);

        session()->flash('success', trans('admin::app.contacts.persons.create-success'));

        return redirect()->route('admin.contacts.persons.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $person = $this->personRepository->findOrFail($id);
        return view('admin::contacts.persons.edit', compact('person'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $contact_numbers
     * @return \Illuminate\View\View
     */
    public function whatsapp($id)
    {
        $person = $this->personRepository->findOrFail($id);
        $contactNumber = collect($person->contact_numbers)->first()['value'];
        $formattedNumber = preg_replace('/\D/', '', $contactNumber); // Hapus karakter non-numerik

        $whatsappUrl = 'https://wa.me/' . $formattedNumber;

        // Mengembalikan view dengan JavaScript untuk membuka link di tab baru
        return view('admin::contacts.persons.open-whatsapp', ['whatsappUrl' => $whatsappUrl]);
    }
    public function PageimportExcel()
    {
        return view('admin::contacts.persons.import-excel');
    }
    public function importExcelData(Request $request)
    {
        $request->validate([
            'import_file_excel' => [
                'required',
                'file'
            ]
        ]);

        try {
            Excel::import(new PersonsImport, $request->file('import_file_excel'), 's3');
            return back()->with('success', 'Data berhasil di upload');
        } catch (\Exception $e) {
            return back()->withError('Error : ' . $e->getMessage())->withInput();
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Webkul\Attribute\Http\Requests\AttributeForm $request
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeForm $request, $id)
    {
        Event::dispatch('contacts.person.update.before', $id);

        $person = $this->personRepository->update($this->sanitizeRequestedPersonData(), $id);

        Event::dispatch('contacts.person.update.after', $person);

        session()->flash('success', trans('admin::app.contacts.persons.update-success'));

        return redirect()->route('admin.contacts.persons.index');
    }

    /**
     * Search person results.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $results = $this->personRepository->findWhere([
            ['name', 'like', '%' . urldecode(request()->input('query')) . '%']
        ]);

        return response()->json($results);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = $this->personRepository->findOrFail($id);

        try {
            Event::dispatch('contacts.person.delete.before', $id);

            $this->personRepository->delete($id);

            Event::dispatch('contacts.person.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.response.destroy-success', ['name' => trans('admin::app.contacts.persons.person')]),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.response.destroy-failed', ['name' => trans('admin::app.contacts.persons.person')]),
            ], 400);
        }
    }

    /**
     * Mass Delete the specified resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        foreach (request('rows') as $personId) {
            Event::dispatch('contact.person.delete.before', $personId);

            $this->personRepository->delete($personId);

            Event::dispatch('contact.person.delete.after', $personId);
        }

        return response()->json([
            'message' => trans('admin::app.response.destroy-success', ['name' => trans('admin::app.contacts.persons.title')])
        ]);
    }

    /**
     * Sanitize requested person data and return the clean array.
     *
     * @return array
     */
    private function sanitizeRequestedPersonData(): array
    {
        $data = request()->all();

        $data['contact_numbers'] = collect($data['contact_numbers'])->filter(function ($number) {
            return ! is_null($number['value']);
        })->toArray();

        return $data;
    }
}
