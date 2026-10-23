<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateHotelRequest;
use App\Http\Requests\Admin\EditHotelRequest;
use App\Http\Requests\Admin\SearchHotelRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\Hotel;
use App\Models\Prefecture;

class HotelController extends Controller
{
    /**
     * Session key for the edit content between input -> confirm -> complete
     */
    private const EDIT_SESSION_KEY = 'hotelEdit';

    /**
     * Session key for the search result page the edit was started from
     */
    private const EDIT_RETURN_URL_SESSION_KEY = 'hotelEditReturnUrl';

    /**
     * Temporary directory (under public/assets/img) for images waiting for confirmation
     */
    private const TMP_IMAGE_DIR = Hotel::IMAGE_DIR . '/tmp';

    /** get methods */

    public function showSearch(): View
    {
        return view('admin.hotel.search', [
            'prefectures' => Prefecture::orderBy('prefecture_id')->get(),
        ]);
    }

    public function showEdit(Request $request): View|RedirectResponse
    {
        $hotel = Hotel::find((int) $request->query('hotel_id'));
        if ($hotel === null) {
            return redirect()
                ->route('adminHotelSearchPage')
                ->with('errorMessage', __('admin.hotel.message.not_found'));
        }

        // back from the confirm screen: restore the content entered before
        $draft = session(self::EDIT_SESSION_KEY);
        $isBack = $request->boolean('back') && ($draft['hotel_id'] ?? null) === $hotel->hotel_id;
        if (!$isBack) {
            $this->discardDraft();
            $draft = null;

            // remember the search result (with its conditions and page) to go back to
            $previousUrl = url()->previous();
            session([self::EDIT_RETURN_URL_SESSION_KEY => str_starts_with($previousUrl, route('adminHotelSearchResult'))
                ? $previousUrl
                : route('adminHotelSearchPage')]);
        }

        return view('admin.hotel.edit', [
            'hotel' => $hotel,
            'draft' => $draft,
            'prefectures' => Prefecture::orderBy('prefecture_id')->get(),
            'returnUrl' => $this->editReturnUrl(),
        ]);
    }

    public function showEditComplete(): View|RedirectResponse
    {
        // only reachable right after an update
        if (!session()->has('hotelName')) {
            return redirect()->route('adminHotelSearchPage');
        }

        return view('admin.hotel.edit-complete', [
            'hotelName' => session('hotelName'),
            'hotelId' => session('hotelId'),
            'returnUrl' => $this->editReturnUrl(),
        ]);
    }

    public function showCreate(): View
    {
        return view('admin.hotel.create', [
            'prefectures' => Prefecture::orderBy('prefecture_id')->get(),
        ]);
    }

    public function searchResult(SearchHotelRequest $request): View
    {
        $var = [];

        $hotelNameToSearch = $request->validated('hotel_name');
        $prefectureIdToSearch = $request->validated('prefecture_id');
        $hotelList = Hotel::getHotelList(
            $hotelNameToSearch,
            $prefectureIdToSearch !== null ? (int) $prefectureIdToSearch : null
        );

        $var['hotelList'] = $hotelList;
        $var['prefectures'] = Prefecture::orderBy('prefecture_id')->get();

        return view('admin.hotel.search', $var);
    }

    /** post methods */

    public function editConfirm(EditHotelRequest $request): View|RedirectResponse
    {
        $hotel = Hotel::with('prefecture')->find($request->validated('hotel_id'));
        $draft = session(self::EDIT_SESSION_KEY);

        // a new image replaces the previous temporary one,
        // otherwise keep the one chosen before going back
        $tmpFilePath = $draft['tmp_file_path'] ?? null;
        if (($draft['hotel_id'] ?? null) !== $hotel->hotel_id) {
            $this->discardDraft();
            $tmpFilePath = null;
        }
        if ($request->hasFile('hotel_image')) {
            $this->deleteImage($tmpFilePath);
            $tmpFilePath = $this->saveImage($request->file('hotel_image'), self::TMP_IMAGE_DIR);
        }

        $draft = [
            'hotel_id' => $hotel->hotel_id,
            'hotel_name' => $request->validated('hotel_name'),
            'prefecture_id' => (int) $request->validated('prefecture_id'),
            'tmp_file_path' => $tmpFilePath,
        ];
        session([self::EDIT_SESSION_KEY => $draft]);

        // only the fields that differ from the current data, shown in the confirm dialog
        $changes = [];
        if ($draft['hotel_name'] !== $hotel->hotel_name) {
            $changes[] = [
                'label' => __('admin.hotel.field.hotel_name'),
                'before' => $hotel->hotel_name,
                'after' => $draft['hotel_name'],
            ];
        }
        if ($draft['prefecture_id'] !== (int) $hotel->prefecture_id) {
            $changes[] = [
                'label' => __('admin.hotel.field.prefecture'),
                'before' => $hotel->prefecture->display_name,
                'after' => Prefecture::find($draft['prefecture_id'])->display_name,
            ];
        }

        // nothing to update: stay on the input screen
        if (empty($changes) && $tmpFilePath === null) {
            return redirect()
                ->route('adminHotelEditPage', ['hotel_id' => $hotel->hotel_id, 'back' => 1])
                ->with('infoMessage', __('admin.hotel.no_changes'));
        }

        return view('admin.hotel.edit-confirm', [
            'hotel' => $hotel,
            'draft' => $draft,
            'changes' => $changes,
            'prefectures' => Prefecture::orderBy('prefecture_id')->get(),
            'returnUrl' => $this->editReturnUrl(),
        ]);
    }

    public function edit(Request $request): RedirectResponse
    {
        $draft = session(self::EDIT_SESSION_KEY);
        $hotel = Hotel::find((int) $request->input('hotel_id'));

        // the session expired or the confirm screen was skipped
        if ($draft === null || $hotel === null || $draft['hotel_id'] !== $hotel->hotel_id) {
            return redirect()
                ->route('adminHotelSearchPage')
                ->with('errorMessage', __('admin.hotel.message.edit_expired'));
        }

        $attributes = [
            'hotel_name' => $draft['hotel_name'],
            'prefecture_id' => $draft['prefecture_id'],
        ];
        if ($draft['tmp_file_path'] !== null) {
            if ($hotel->hasUploadedImage()) {
                $this->deleteImage($hotel->file_path);
            }
            $attributes['file_path'] = $this->moveImage($draft['tmp_file_path'], Hotel::IMAGE_DIR);
        }

        $hotel->update($attributes);
        session()->forget(self::EDIT_SESSION_KEY);

        return redirect()
            ->route('adminHotelEditCompletePage')
            ->with('successMessage', __('admin.hotel.message.updated', ['name' => $hotel->hotel_name]))
            ->with('hotelName', $hotel->hotel_name)
            ->with('hotelId', $hotel->hotel_id);
    }

    public function create(CreateHotelRequest $request): RedirectResponse
    {
        $hotel = Hotel::create([
            'hotel_name' => $request->validated('hotel_name'),
            'prefecture_id' => (int) $request->validated('prefecture_id'),
            'file_path' => $this->saveImage($request->file('hotel_image'), Hotel::IMAGE_DIR),
        ]);

        // stay on the (empty) create form to add the next one
        return redirect()
            ->route('adminHotelCreatePage')
            ->with('successMessage', __('admin.hotel.message.created', ['name' => $hotel->hotel_name]));
    }

    public function delete(Request $request): RedirectResponse
    {
        $hotel = Hotel::find((int) $request->input('hotel_id'));
        if ($hotel === null) {
            return back()->with('errorMessage', __('admin.hotel.message.not_found'));
        }

        if ($hotel->bookings()->exists()) {
            return back()->with('errorMessage', __('admin.hotel.message.has_bookings', ['name' => $hotel->hotel_name]));
        }

        if ($hotel->hasUploadedImage()) {
            $this->deleteImage($hotel->file_path);
        }
        $hotel->delete();

        return back()->with('successMessage', __('admin.hotel.message.deleted', ['name' => $hotel->hotel_name]));
    }

    /** private methods */

    /**
     * Search result page to go back to from the edit screens
     */
    private function editReturnUrl(): string
    {
        return session(self::EDIT_RETURN_URL_SESSION_KEY, route('adminHotelSearchPage'));
    }

    /**
     * Save an uploaded image under public/assets/img/{dir}
     *
     * @return string file path relative to public/assets/img
     */
    private function saveImage(UploadedFile $file, string $dir): string
    {
        $fileName = Str::uuid() . '.' . $file->extension();
        $file->move(public_path('assets/img/' . $dir), $fileName);

        return $dir . '/' . $fileName;
    }

    /**
     * Move an image to public/assets/img/{dir}
     *
     * @return string file path relative to public/assets/img
     */
    private function moveImage(string $filePath, string $dir): string
    {
        File::ensureDirectoryExists(public_path('assets/img/' . $dir));

        $newFilePath = $dir . '/' . basename($filePath);
        File::move(public_path('assets/img/' . $filePath), public_path('assets/img/' . $newFilePath));

        return $newFilePath;
    }

    private function deleteImage(?string $filePath): void
    {
        if ($filePath !== null) {
            File::delete(public_path('assets/img/' . $filePath));
        }
    }

    /**
     * Remove an unfinished edit and its temporary image
     */
    private function discardDraft(): void
    {
        $this->deleteImage(session(self::EDIT_SESSION_KEY)['tmp_file_path'] ?? null);
        session()->forget(self::EDIT_SESSION_KEY);
    }
}
