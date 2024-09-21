<?php

namespace App\Services\Ticket;

use App\Enums\Ticket\TicketImportanceStatus;
use App\Enums\Ticket\TicketStatus;
use App\Filters\Ticket\FilterTicket;
use App\Models\Tiket\Ticket;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TicketService{

    private $ticket;
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function allTickets()
    {
        $tickets = QueryBuilder::for(Ticket::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterTicket()), // Add a custom search filter
                AllowedFilter::exact('status'),
                AllowedFilter::exact('importance'),
                AllowedFilter::exact('company', 'company_id'),
                AllowedFilter::exact('tag', 'tag_id'),
            ])->get();

        return $tickets;

    }

    public function createTicket(array $ticketData): Ticket
    {

        $ticket = Ticket::create([
            'company_id' => $ticketData['companyId'],
            'status' => TicketStatus::from($ticketData['status'])->value,
            'importance' => TicketImportanceStatus::from($ticketData['importance'])->value,
            'description' => $ticketData['description'],
            'customer_id' => $ticketData['customerId'],
            'branch_id' => $ticketData['branchId'],
            'tag_id' => $ticketData['tagId']??null
        ]);

        return $ticket;

    }

    public function editTicket(int $ticketId)
    {
        return Ticket::with('attachments')->find($ticketId);
    }

    public function updateTicket(array $ticketData): Ticket
    {

        $ticket = Ticket::find($ticketData['ticketId']);

        $ticket->update([
            'company_id' => $ticketData['companyId'],
            'status' => TicketStatus::from($ticketData['status'])->value,
            'importance' => TicketImportanceStatus::from($ticketData['importance'])->value,
            'description' => $ticketData['description'],
            'customer_id' => $ticketData['customerId'],
            'branch_id' => $ticketData['branchId'],
            'tag_id' => $ticketData['tagId']??null
        ]);

        return $ticket;


    }


    public function deleteTicket(int $ticketId)
    {

        return Ticket::find($ticketId)->delete();

    }


}
