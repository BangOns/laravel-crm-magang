<?php

namespace Webkul\CustomWorkflow\Contact;

use Illuminate\Support\Facades\DB;
use Webkul\Admin\Traits\ProvideDropdownOptions;
use Webkul\UI\DataGrid\DataGrid;

class CustomWorkflow extends DataGrid
{
    use ProvideDropdownOptions;

    /**
     * Create a new message instance.
     * @param object $user
     * @return void
     */
    public function __construct(private $user) {}


    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('persons')
            ->addSelect(
                'persons.id',
                'persons.name as person_name',
                'persons.emails',
                'persons.contact_numbers',
                'organizations.name as organization',
                'organizations.id as organization_id'
            )
            ->leftJoin('organizations', 'persons.organization_id', '=', 'organizations.id');

        $this->addFilter('id', 'persons.id');
        $this->addFilter('person_name', 'persons.name');
        $this->addFilter('organization', 'organizations.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'string',
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'    => 'person_name',
            'label'    => trans('admin::app.datagrid.name'),
            'type'     => 'string',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'    => 'emails',
            'label'    => trans('admin::app.datagrid.emails'),
            'type'     => 'string',
            'sortable' => false,
            'closure'  => function ($row) {
                $emails = json_decode($row->emails, true);

                if ($emails) {
                    return collect($emails)->pluck('value')->join(', ');
                }
            },
        ]);

        $this->addColumn([
            'index'    => 'contact_numbers',
            'label'    => trans('admin::app.datagrid.contact_numbers'),
            'type'     => 'string',
            'sortable' => false,
            'closure'  => function ($row) {
                $contactNumbers = json_decode($row->contact_numbers, true);

                if ($contactNumbers) {
                    return collect($contactNumbers)->pluck('value')->join(', ');
                }
            },
        ]);

        $this->addColumn([
            'index'            => 'organization',
            'label'            => trans('admin::app.datagrid.organization_name'),
            'type'             => 'dropdown',
            'dropdown_options' => $this->getOrganizationDropdownOptions(),
            'sortable'         => false,
            'closure'  => function ($row) {
                return "<a href='" . route('admin.contacts.organizations.edit', $row->organization_id) . "' target='_blank'>" . $row->organization . "</a>";
            },
        ]);
    }
}
