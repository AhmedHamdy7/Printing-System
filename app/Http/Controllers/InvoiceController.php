<?php

namespace App\Http\Controllers;

use App\Repositories\InvoiceRepository;
use App\Repositories\ProductRepository;
use App\Services\InvoiceService;
use App\Services\PdfService;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Exception;

class InvoiceController extends Controller
{
    protected $invoiceRepository;
    protected $productRepository;
    protected $invoiceService;
    protected $pdfService;

    public function __construct(
        InvoiceRepository $invoiceRepository,
        ProductRepository $productRepository,
        InvoiceService $invoiceService,
        PdfService $pdfService
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->productRepository = $productRepository;
        $this->invoiceService = $invoiceService;
        $this->pdfService = $pdfService;
    }
    public function index()
    {
        try {
            $userId = auth()->user()->hasRole('employee') ? auth()->id() : null;
            $invoices = $this->invoiceRepository->getAllWithFilters($userId);

            return view('invoices.index', compact('invoices'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load invoices: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $products = $this->productRepository->getActive();
            return view('invoices.create', compact('products'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load create form: ' . $e->getMessage());
        }
    }

    public function store(StoreInvoiceRequest $request)
    {
        try {
            $this->invoiceService->createInvoice(
                $request->validated(),
                auth()->id()
            );

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice created successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $invoice = $this->invoiceRepository->findWithRelations($id);

            if (!$this->invoiceService->canUserAccessInvoice(auth()->user(), $invoice)) {
                abort(403, 'Unauthorized action.');
            }

            return view('invoices.show', compact('invoice'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load invoice: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $invoice = $this->invoiceRepository->findWithRelations($id);

            if (!$this->invoiceService->canUserAccessInvoice(auth()->user(), $invoice)) {
                abort(403, 'Unauthorized action.');
            }

            $products = $this->productRepository->getActive();
            return view('invoices.edit', compact('invoice', 'products'));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    public function update(UpdateInvoiceRequest $request, $id)
    {
        try {
            $invoice = $this->invoiceRepository->findOrFail($id);

            if (!$this->invoiceService->canUserAccessInvoice(auth()->user(), $invoice)) {
                abort(403, 'Unauthorized action.');
            }

            $this->invoiceService->updateInvoice($id, $request->validated());

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $invoice = $this->invoiceRepository->findOrFail($id);

            if (!$this->invoiceService->canUserAccessInvoice(auth()->user(), $invoice)) {
                abort(403, 'Unauthorized action.');
            }

            $this->invoiceRepository->delete($id);

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete invoice: ' . $e->getMessage());
        }
    }

    public function pdf($id)
    {
        try {
            $invoice = $this->invoiceRepository->findOrFail($id);

            if (!$this->invoiceService->canUserAccessInvoice(auth()->user(), $invoice)) {
                abort(403, 'Unauthorized action.');
            }

            return $this->pdfService->generateInvoicePdf($invoice);
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}
