<?php

namespace Yajra\DataTables\Html\Options\Plugins;

/**
 * DataTables - FixedHeader plugin option builder.
 *
 * @see https://datatables.net/extensions/fixedheader/
 * @see https://datatables.net/reference/option/fixedHeader
 * @see https://datatables.net/reference/option/#fixedHeader
 */
trait FixedHeader
{
    /**
     * Set fixedHeader option value.
     *
     * @param  bool|array  $value
     * @return $this
     * @see https://datatables.net/reference/option/fixedHeader
     */
    public function fixedHeader(bool|array $value = true): static
    {
        $this->attributes['fixedHeader'] = $value;

        return $this;
    }

    /**
     * Set fixedHeader footer option value.
     *
     * @param  bool  $value
     * @return $this
     * @see https://datatables.net/reference/option/fixedHeader.footer
     */
    public function fixedHeaderFooter(bool $value = true): static
    {
        return $this->fixedHeader(['footer' => $value]);
    }

    /**
     * Set fixedHeader footerOffset option value.
     *
     * @param  int  $value
     * @return $this
     * @see https://datatables.net/reference/option/fixedHeader.footerOffset
     */
    public function fixedHeaderFooterOffset(int $value = 0): static
    {
        return $this->fixedHeader(['footerOffset' => $value]);
    }

    /**
     * Set fixedHeader header option value.
     *
     * @param  bool  $value
     * @return $this
     * @see https://datatables.net/reference/option/fixedHeader.header
     */
    public function fixedHeaderHeader(bool $value = true): static
    {
        return $this->fixedHeader(['header' => $value]);
    }

    /**
     * Set fixedHeader headerOffset option value.
     *
     * @param  int  $value
     * @return $this
     * @see https://datatables.net/reference/option/fixedHeader.headerOffset
     */
    public function fixedHeaderHeaderOffset(int $value = 0): static
    {
        return $this->fixedHeader(['headerOffset' => $value]);
    }
}
