<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateHotelRequest;
use App\Http\Requests\Admin\EditHotelRequest;
use App\Http\Requests\Admin\SearchHotelRequest;
use App\Models\Hotel;
use App\Models\Prefecture;
use App\Services\HotelImageService;
use App\Support\HotelEditDraft;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class HotelController extends Controller
{
    /**
     * Session key for the search result page the edit was started from
     */
    private const EDIT_RETURN_URL_SESSION_KEY = 'hotelEditReturnUrl';

    public function __construct(
        private readonly HotelImageService $images,
    ) {}

    /** get methods */
    public function showSearch(): View
    {
        return view('admin.hotel.search', [
            'prefectures' => $this->prefectures(),
        ]);
    }

    public function searchResult(SearchHotelRequest $request): View
    {
        $prefectureId = $request->validated('prefecture_id');

        return view('admin.hotel.search', [
            'hotelList' => Hotel::getHotelList(
                $request->validated('hotel_name'),
                $prefectureId !== null ? (int) $prefectureId : null,
            ),
            'prefectures' => $this->prefectures(),
        ]);
    }

    public function showCreate(): View
    {
        return view('admin.hotel.create', [
            'prefectures' => $this->prefectures(),
        ]);
    }

    public function showEdit(Request $request): View|RedirectResponse
    {
        $hotel = Hotel::find((int) $request->query('hotel_id'));
        if ($hotel === null) {
            return $this->redirectToSearch('errorMessage', __('admin.hotel.message.not_found'));
        }

        // back from the confirm screen: restore the content entered before
        $draft = $request->boolean('back') ? HotelEditDraft::of($hotel) : null;
        if ($draft === null) {
            $this->discardDraft();
            $this->rememberReturnUrl();
        }

        return view('admin.hotel.edit', [
            'hotel' => $hotel,
            'draft' => $draft,
            'prefectures' => $this->prefectures(),
            'returnUrl' => $this->returnUrl(),
        ]);
    }

    public function showEditComplete(): View|RedirectResponse
    {
        // only reachable right after an update
        $hotel = Hotel::find(session('updatedHotelId'));
        if ($hotel === null) {
            return redirect()->route('adminHotelSearchPage');
        }

        return view('admin.hotel.edit-complete', [
            'hotel' => $hotel,
            'returnUrl' => $this->returnUrl(),
        ]);
    }

    /** post methods */
    public function create(CreateHotelRequest $request): RedirectResponse
    {
        $imagePath = $this->images->store($request->file('hotel_image'));

        try {
            $hotel = Hotel::create([
                'hotel_name' => $request->validated('hotel_name'),
                'prefecture_id' => $request->validated('prefecture_id'),
                'file_path' => $imagePath,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to create hotel', ['handler' => 'CreateHotel', 'image' => $imagePath, 'error' => $e->getMessage()]);
            // do not leave an image without a hotel
            $this->images->delete($imagePath);
            throw $e;
        }

        Log::info('Hotel created', ['handler' => 'CreateHotel', 'hotel_id' => $hotel->hotel_id]);

        // stay on the (empty) create form to add the next one
        return redirect()
            ->route('adminHotelCreatePage')
            ->with('successMessage', __('admin.hotel.message.created', ['name' => $hotel->hotel_name]));
    }

    public function editConfirm(EditHotelRequest $request): View|RedirectResponse
    {
        $hotel = Hotel::with('prefecture')->findOrFail($request->validated('hotel_id'));

        // a draft left from another hotel is dropped with its temporary image
        if (HotelEditDraft::current() !== null && HotelEditDraft::of($hotel) === null) {
            $this->discardDraft();
        }

        // a new image replaces the previous temporary one,
        // otherwise keep the one chosen before going back
        $tmpImagePath = HotelEditDraft::of($hotel)?->tmpImagePath;
        if ($request->hasFile('hotel_image')) {
            $this->images->delete($tmpImagePath);
            $tmpImagePath = $this->images->storeTemporary($request->file('hotel_image'));
        }

        $draft = new HotelEditDraft(
            hotelId: $hotel->hotel_id,
            hotelName: $request->validated('hotel_name'),
            prefectureId: (int) $request->validated('prefecture_id'),
            tmpImagePath: $tmpImagePath,
        );
        $draft->save();

        // nothing to update: stay on the input screen
        if (! $draft->hasChangesFrom($hotel)) {
            return redirect()
                ->route('adminHotelEditPage', ['hotel_id' => $hotel->hotel_id, 'back' => 1])
                ->with('infoMessage', __('admin.hotel.no_changes'));
        }

        return view('admin.hotel.edit-confirm', [
            'hotel' => $hotel,
            'draft' => $draft,
            'changes' => $draft->changesFrom($hotel),
            'prefectures' => $this->prefectures(),
            'returnUrl' => $this->returnUrl(),
        ]);
    }

    public function edit(Request $request): RedirectResponse
    {
        $hotel = Hotel::find((int) $request->input('hotel_id'));
        $draft = $hotel !== null ? HotelEditDraft::of($hotel) : null;

        // the session expired or the confirm screen was skipped
        if ($draft === null) {
            Log::warning('Hotel edit draft not found', ['handler' => 'UpdateHotel', 'hotel_id' => $request->input('hotel_id')]);

            return $this->redirectToSearch('errorMessage', __('admin.hotel.message.edit_expired'));
        }

        $oldImagePath = $hotel->file_path;
        $newImagePath = $draft->tmpImagePath !== null ? $this->images->promote($draft->tmpImagePath) : null;

        try {
            $hotel->update([
                'hotel_name' => $draft->hotelName,
                'prefecture_id' => $draft->prefectureId,
                'file_path' => $newImagePath ?? $oldImagePath,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to update hotel', ['handler' => 'UpdateHotel', 'hotel_id' => $hotel->hotel_id, 'image' => $newImagePath, 'error' => $e->getMessage()]);
            $this->images->delete($newImagePath);
            throw $e;
        }

        // the old image is removed only after the update succeeded
        if ($newImagePath !== null) {
            $this->images->delete($oldImagePath);
        }
        HotelEditDraft::forget();

        Log::info('Hotel updated', ['handler' => 'UpdateHotel', 'hotel_id' => $hotel->hotel_id, 'image_changed' => $newImagePath !== null]);

        return redirect()
            ->route('adminHotelEditCompletePage')
            ->with('successMessage', __('admin.hotel.message.updated', ['name' => $hotel->hotel_name]))
            ->with('updatedHotelId', $hotel->hotel_id);
    }

    public function delete(Request $request): RedirectResponse
    {
        $hotel = Hotel::find((int) $request->input('hotel_id'));
        if ($hotel === null) {
            Log::warning('Hotel to delete not found', ['handler' => 'DeleteHotel', 'hotel_id' => $request->input('hotel_id')]);

            return back()->with('errorMessage', __('admin.hotel.message.not_found'));
        }

        if ($hotel->bookings()->exists()) {
            Log::info('Hotel with bookings was not deleted', ['handler' => 'DeleteHotel', 'hotel_id' => $hotel->hotel_id]);

            return back()->with('errorMessage', __('admin.hotel.message.has_bookings', ['name' => $hotel->hotel_name]));
        }

        $hotel->delete();
        // the image is removed only after the record is gone
        $this->images->delete($hotel->file_path);

        Log::info('Hotel deleted', ['handler' => 'DeleteHotel', 'hotel_id' => $hotel->hotel_id]);

        return back()->with('successMessage', __('admin.hotel.message.deleted', ['name' => $hotel->hotel_name]));
    }

    /** private methods */
    private function prefectures(): Collection
    {
        return Prefecture::orderBy('prefecture_id')->get();
    }

    private function redirectToSearch(string $flashKey, string $message): RedirectResponse
    {
        return redirect()->route('adminHotelSearchPage')->with($flashKey, $message);
    }

    /**
     * Remove an unfinished edit and its temporary image
     */
    private function discardDraft(): void
    {
        $this->images->delete(HotelEditDraft::current()?->tmpImagePath);
        HotelEditDraft::forget();
    }

    /**
     * Remember the search result (with its conditions and page) the edit was started from
     */
    private function rememberReturnUrl(): void
    {
        $previousUrl = url()->previous();

        session([self::EDIT_RETURN_URL_SESSION_KEY => str_starts_with($previousUrl, route('adminHotelSearchResult'))
            ? $previousUrl
            : route('adminHotelSearchPage')]);
    }

    private function returnUrl(): string
    {
        return session(self::EDIT_RETURN_URL_SESSION_KEY, route('adminHotelSearchPage'));
    }
}
